from flask import Flask, Response,request,render_template
from pprint import pprint
import json
from urllib.parse import quote_plus,quote,urlencode
import requests
import copy

#import solr schema
exec(open("./schema.py").read())

app = Flask(__name__)

@app.route('/') 
@app.route('/search')
def search():
    print("IN SEARCH")
    response = Response()
    #response.headers['Content-Type'] = 'application/json'
    unsafe_query = {
        "q": request.args.get('q'),
        "f": request.args.get('f'),
        "start": request.args.get('start'),
        "rows": request.args.get('rows'),
    }
    fq = request.args.get('fq')
    if not fq==None:
        fqs = fq.split(';')
        filter_queries  = {}
        for fq in fqs:
            filter_query = fq.split(':')
            if len(filter_query)==2:
                filter_queries[filter_query[0]] = filter_query[1]
        unsafe_query['fq'] = filter_queries
    #pprint(filter_queries)
    #pprint(request.args.to_dict())

    #with open('carousel.json') as data_file:    
    #    carousel = json.load(data_file)
    #check for missing values and set to default if missing
    if unsafe_query['q']==None or unsafe_query['f']==None:
        return render_template("search.html")
    if unsafe_query['q']=='':
        unsafe_query['q']='*'
    if unsafe_query['f']=='':
        unsafe_query['f']='all'
    unsafe_query['start'] = unsafe_query['start'] if not unsafe_query['start']==None else "0"
    unsafe_query['rows'] = unsafe_query['rows'] if not unsafe_query['rows']==None else "20"
    r = requests.post(search_url+'/searchapi',json=unsafe_query)
    print(search_url+'/searchapi')
    #print(r.status_code)
    #print(r.text)
    search_results = json.loads(r.text)
    pprint(search_results)
    facets = {}
    filter_queries = unsafe_query['fq'] if 'fq' in unsafe_query else {}
    for f,data in search_results['facets']['facet_fields'].items():
        facet_counter = 0
        #print(type(data))
        if len(data)==0:
            continue
        #print(data)
        while facet_counter < len(data):
            title = data[facet_counter]
            num = data[facet_counter+1]
            if not num==0:
                facet = {
                    "expanded": True if f in filter_queries else False,
                    "title":title,
                    "count":num,
                    "url": build_facet_filter_query(unsafe_query,title,f),
                    "breadcrumb":build_facet_breadcrumb_query(unsafe_query,title,f)
                    #"name":f
                }
                if f in filter_queries and filter_queries[f] == title:
                    facet['bold'] = True
                else:
                    facet['bold'] = False
                if f in facets:
                    facets[f].append(facet)
                else:
                    facets[f] = [facet]
            facet_counter += 2
    #pprint(facets)
    ordered_facets = []
    for (name,title) in facet_fields.items():
        if name in facets:
            ordered_facets.append([title,facets[name]])
    pprint(ordered_facets)
    #response.set_data(r.content)

    results = search_results['response']['docs']
    highlighting = search_results['highlighting']
    #pprint(highlighting)
    new_results = []
    for doc in results:
        if doc['url'] in highlighting:
            new_doc = doc
            for key,item in highlighting[doc['url']].items():
                new_doc[key] = strip_html(item[0]).replace("{{mark}}","<mark>").replace("{{/mark}}","</mark>")
            new_results.append(new_doc)
        else:
            new_results.append(doc)

    results = new_results
    new_results = []
    for doc in results:
        new_doc = {}
        has_value=False
        for key,item in doc.items():
            if isinstance(item,str):
                if not item=="":
                    has_value=True
                    new_doc[key] = [item]
            else:
                has_value=True
                new_doc[key] = item
        if has_value:
            new_results.append(new_doc)
    #pprint(new_results)

    #build nav_string
    start = int(search_results['responseHeader']['params']['start'])
    rows = int(search_results['responseHeader']['params']['rows'])
    numFound = int(search_results['response']['numFound'])
    nav_string = "Showing results " + str(start+1) +" to "
    if numFound<=(start+rows):
        nav_string += str(numFound)
    else:
        nav_string += str(start+rows) + " of " + str(numFound)
    if numFound==0:
        nav_string = "No results"
    
    #build prev and next queries
    prev_query,next_query = build_nav_queries(unsafe_query,start,rows,numFound)

    print("news url: ",next_query)

    return render_template("search.html",
                has_results = True,
                facets = ordered_facets,
                search_results=new_results,brief_display_fields=brief_display_fields,solr_field_names=solr_field_names,
                start = start,
                rows = rows,
                numFound = numFound,
                nav_string = nav_string,
                prev_query = prev_query,
                next_query=next_query,
                query=unsafe_query['q'],
                field=unsafe_query['f'])

def build_nav_queries(query,start,rows,numFound):
    current_query = copy.deepcopy(query)

    new_start = int(current_query['start'])+rows
    print("New start",new_start)
    nexturl = "search?q="+quote_plus(current_query['q'])
    nexturl = nexturl + "&f=" + quote_plus(current_query['f'])
    nexturl = nexturl + "&start=" + quote_plus(str(new_start))
    nexturl = nexturl + "&rows=" + quote_plus(current_query['rows'])
    if 'fq' in current_query:
        fqs = "&fq="
        for q,f in current_query['fq'].items():
            fqs  = fqs + q + ":\"" + f.replace("\"","") + "\";"
        nexturl = nexturl + fqs
    if new_start>numFound:
        nexturl = None
    print("news url: ",nexturl)
    new_start = int(current_query['start'])-rows

    prevurl = "search?q="+quote_plus(current_query['q'])
    prevurl = prevurl + "&f=" + quote_plus(current_query['f'])
    if new_start<0:
        prevurl = prevurl + "&start=" + quote_plus(str(0))
    else:
        prevurl = prevurl + "&start=" + quote_plus(str(new_start))

    prevurl = prevurl + "&rows=" + quote_plus(current_query['rows'])
    if 'fq' in current_query:
        fqs = "&fq="
        for q,f in current_query['fq'].items():
            fqs  = fqs + q + ":\"" + f.replace("\"","") + "\";"
        prevurl = prevurl + fqs

    if start==0:
        prevurl = None
    return prevurl,nexturl

def build_facet_filter_query(current_query,query,field):
    current_query = copy.deepcopy(current_query)
    #pprint(current_query)
    if 'fq' in current_query:
        current_query['fq'][field] = query
    else:
        current_query['fq'] = {field:query}
    url = "search?q="+quote_plus(current_query['q'])
    url = url + "&f=" + quote_plus(current_query['f'])
    url = url + "&start=" + quote_plus(current_query['start'])
    url = url + "&rows=" + quote_plus(current_query['rows'])
    fqs = "&fq="
    for q,f in current_query['fq'].items():
        fqs  = fqs + q + ":\"" + f.replace("\"","") + "\";"
    url = url + fqs
    return url

def build_facet_breadcrumb_query(current_query,query,field):
    current_query = copy.deepcopy(current_query)
    #pprint(current_query)
    if 'fq' in current_query:
        fq = current_query['fq']
        fq = {key:val for key,val in fq.items() if (key!=field and val!=query)}
        #pprint (fq)
        current_query['fq'] = fq
    #else:
    #    current_query['fq'] = {field:query}
    url = "search?q="+quote_plus(current_query['q'])
    url = url + "&f=" + quote_plus(current_query['f'])
    url = url + "&start=" + quote_plus(current_query['start'])
    url = url + "&rows=" + quote_plus(current_query['rows'])
    fqs=''
    if 'fq' in current_query:
        fqs = "&fq="
        for q,f in current_query['fq'].items():
            fqs  = fqs + q + ":\"" + f.replace("\"","") + "\";"
    url = url + fqs
    return url

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

@app.route('/searchapi',methods=['POST'])
def searchapi():
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
        pprint(solr_response)
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
if __name__ == "__main__":
    app.run(host='0.0.0.0',debug=True,port=5000)
