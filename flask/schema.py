

solr_core_name = 'indexiuris'

solr_url = 'http://localhost:8983/solr/'+ solr_core_name+ '/'

search_result_rows = 20

solr_results_highlight_tag = 'mark'

solr_field_names ={
#"custom_namespace" : {"field_title" : "Digital Collection",
#	"display" : "brief"},
#"rdf_about" : {"field_title" : "Contributing Institution",
#	"display" : "brief"},
"archive" : {"field_title" : "Title",
	"display" : "brief"},
"title" : {"field_title" : "Title",
	"display" : "brief"},
"url" : {"field_title" : "Title",
	"display" : "brief"},
"type_original" : {"field_title" : "Title",
	"display" : "brief"},
"type_content" : {"field_title" : "Type of Content",
	"display" : "brief"},
"type_digital" : {"field_title" : "Type of Digital Surrogate",
	"display" : "full"},
"role_ALL" : {"field_title" : "Title",
	"display" : "brief"},
"genre" : {"field_title" : "Title",
	"display" : "brief"},
"date_human" : {"field_title" : "Title",
	"display" : "brief"},
"date_digital" : {"field_title" : "Title",
	"display" : "brief"},
"provenance" : {"field_title" : "Title",
	"display" : "brief"},
"place_of_composition" : {"field_title" : "Title",
	"display" : "brief"},
"origin" : {"field_title" : "Title",
	"display" : "brief"},
"shelfmark" : {"field_title" : "Title",
	"display" : "brief"},
"is_freeculture" : {"field_title" : "Title",
	"display" : "brief"},
"full_text" : {"field_title" : "Title",
	"display" : "brief"},
"alternative_title" : {"field_title" : "Title",
	"display" : "brief"},
"source" : {"field_title" : "Title",
	"display" : "brief"},


	
"text_divisions" : {"field_title" : "Title",
	"display" : "brief"},
"is_ocr" : {"field_title" : "Title",
	"display" : "brief"},

"file_format" : {"field_title" : "Title",
	"display" : "brief"},

"discipline" : {"field_title" : "Title",
	"display" : "brief"},
"language" : {"field_title" : "Title",
	"display" : "brief"},

"thumbnail_url" : {"field_title" : "Thumbnail URL",
	"display" : "full"},


"file_format" : {"field_title" : "File Format",
	"display" : "full"},
"alternative_title" : {"field_title" : "Alternative Title",
	"display" : "full"},
"notes" : {"field_title" : "Notes",
	"display" : "full"}
}

brief_display_fields = []
for name,info in solr_field_names.items():
	if info['display']=='brief':
		brief_display_fields.append(name)

facet_fields = {
		"archive_facet" : "Digital Collection",
		"contributing_institution_facet" : "Contributing Institution",
		"subject_heading_facet" : "LC Subject Headings",
		"type_content" : "Type of Content",
		"file_format" : "File Format",
		"language" : "Language"
		#"date"
}

search_fields = {
		"contributing_institution",
		"url",
		"title",
		"type_content",
		"type_digital",
		"role_ALL" ,
		"geolocation_human",
		"alternative_title",
		"description",
		"full_text",
		"type_physical",
		"shelfmark",
		"subject_heading",
		"extent",
		"copyright_holder",
		"use_permissions",
		"language",
		"notes",
}

advanced_search_fields = {
		"all" : "Search all fields",
		"title" : "Title (title and alternative title fields)",
		"description" : "Description",
		"notes" : "Notes",
		"shelfmark" : "Shelfmark",
		"subject" : "LC Subject Headings",
		"role" : "Roles (authors, editors, etc.)"
}


import re, cgi

def strip_html(html):
	#from https://stackoverflow.com/a/19730306

	tag_re = re.compile(r'(<!--.*?-->|<[^>]*>)')

	# Remove well-formed tags, fixing mistakes by legitimate users
	no_tags = tag_re.sub('', html)

	# Clean up anything else by escaping
	ready_for_web = cgi.escape(no_tags)
	return ready_for_web