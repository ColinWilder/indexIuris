from flask import Flask, Response,request
from pprint import pprint
import json
from urllib.parse import quote_plus,quote,urlencode
import requests

#import solr schema
exec(open("./schema.py").read())

app = Flask(__name__)

@app.route('/simplesearch')
def simple_search():
    query = request.args.get('q')
    start = request.args.get('start')
    rows = request.args.get('rows')
    archive = request.args.get('archive')
    response = Response()
    response.headers['Content-Type'] = 'application/json'
    if query==None or start==None:
        response.set_data(json_encode({'error':'Parameter not set'}))
        return response
    
    #check rows value
    try:
        rows = int(rows)
    except ValueError:
        print("Warning: Rows value error")
        rows = None
    rows = 20 if rows==None else rows

    #check start vaue
    try:
        start = int(start)
    except ValueError:
        print("Warning: Start value error")
        start = None
    start = 0 if start==None else start

    #create search query
    search_query = {
        'is_full_text':True,
        'query_array':[['all','AND',query]],
        'start': start,
        'rows': rows,
    }

    #check if archive is set
    if not archive==None:
        search_query['fq'] =  [archive]
        search_query['fq_field']= ['archive']
    else:
        search_query['fq'] =  []
        search_query['fq_field']= []
        print ("Notice: archive not set")
    #pprint(search_query)
    try:
        solr_response = get_solr_results (search_query)
        if not solr_response['responseHeader']['status'] ==0:
            response.set_data(json_encode({'error':'Unspecified solr error. Please contact a system administrator for assistance.'}))
        response.set_data(json_encode({
        'error':'None',
        'response':solr_response['response']
        }))
        return response
    except ValueError:
        response.set_data(json_encode({'error':'Internal solr error. Value error. Please contact a system administrator for assistance.'}))
        return response
    except TimeoutError(err):
        response.set_data(json_encode({'error':'Internal solr error. Timeout error. Please contact a system administrator for assistance.',
        'err':err}))
        return response    

def json_encode(data):
    return json.dumps(data,ensure_ascii=False,indent=4, sort_keys=True)

def get_solr_results(search_query):
    query_string = build_solr_query(search_query)
    print(query_string)
    r = requests.get(query_string)
    pprint(r)
    if not r.status_code==200: #solr down?
        solr_response = json.loads(r.text)
        error = 'Solr error.'+ 'details: '+solr_response['error']['msg']
        raise TimeoutError(error)
    try: #check if valid json from solr
        solr_response = json.loads(r.text)
        return solr_response
    except ValueError:
        raise ValueError("Invalid json from solr")


def build_solr_query(query):
    query_string = 'q='
    query_array = query['query_array']
    counter = 0
    for partial in query_array:
        if partial[2]=='': #check it's not empty
            continue
        if not counter==0:
            query_string = query_string + quote_plus(partial[1]) + '+' #add op
            counter +=1
        if partial[0] == 'all':
            query_string = query_string + build_query_for_all_fields(quote_plus(partial[2]))
        else:
            query_string = query_string + quote_plus(partial[0]) + ':(' + quote_plus(partial[2])+ ')%0A'
    
    #filter queries
    counter = 0
    filter_queries = zip(query['fq_field'],query['fq'])
    for fq_field,fq in filter_queries:
        query_string = query_string + '&fq=' + quote_plus(str(fq_field)) + ':' + quote_plus(str(fq))
    
    query_string = solr_url + 'select?' + query_string + '&start=' + str(query['start'])+'&rows=' + str(query['rows']) + '&wt=json&hl=true&hl.simple.pre='+quote_plus('{{'+solr_results_highlight_tag + '}}')+                 '&hl.simple.post='+quote_plus('{{/'+solr_results_highlight_tag+'}}')+'&hl.fl=*&facet=true'

    #add facet fields to query
    for key,value in facet_fields.items():
        query_string = query_string + '&facet.field='+key
    
    #add stats param for years
    query_string = query_string+'&stats=true&stats.field=years&indent=true'
    return query_string

def build_query_for_all_fields(query):
    query_string = ''
    for field in search_fields:
        query_string = query_string + field + ':(' + query+')%0A'
    return query_string

@app.route('/search',methods=['POST'])
def search():
    data = request.get_json()
    pprint(data)
    query = data['q'] if 'q' in data else None
    start = data['start'] if 'start' in data else None
    rows = data['rows'] if 'rows' in data else None
    fq = data['fq'] if 'fq' in data else None
    response = Response()
    response.headers['Content-Type'] = 'application/json'
    if query==None or start==None:
        response.set_data(json_encode({'error':'Parameter not set'}))
        return response
    
    #check rows value
    try:
        rows = int(rows)
    except ValueError:
        print("Warning: Rows value error")
        rows = None
    except TypeError:
        print("Warning: Rows value error")
        rows = None
    rows = 20 if rows==None else rows

    #check start vaue
    try:
        start = int(start)
    except ValueError:
        print("Warning: Start value error")
        start = None
    start = 0 if start==None else start

    #create search query
    search_query = {
        'is_full_text':True,
        'query_array':[['all','AND',query]],
        'start': start,
        'rows': rows,
    }

    #set filter queries
    if not fq==None:
        search_query['fq'] = []
        search_query['fq_field'] = []
        if type(fq) is dict:
            for key,val in fq.items():
                search_query['fq_field'].append(key)
                search_query['fq'].append(val)
    else:
        search_query['fq'] =  []
        search_query['fq_field']= []
        print ("Notice: filter query not set")
    pprint(search_query)
    try:
        solr_response = get_solr_results (search_query)
        if not solr_response['responseHeader']['status'] ==0:
            response.set_data(json_encode({'error':'Unspecified solr. Please contact a system administrator for assistance.'}))
        response.headers['Content-Type'] = 'application/json'
        response.set_data(json_encode({
        'error':'None',
        'response':solr_response['response'],
        'facets': solr_response['facet_counts'],
        'highlighting': solr_response['highlighting'],
        'responseHeader':solr_response['responseHeader']
        }))
        return response
    except ValueError:
        response.set_data(json_encode({'error':'Internal solr error. Value error. Please contact a system administrator for assistance.'}))
        return response
    except TimeoutError as err:
        response.set_data(json_encode(str(err)))
        return response
        #response.set_data(json_encode({'error':'Internal solr error. Timeout error. Please contact a system administrator for assistance.','err':err}))