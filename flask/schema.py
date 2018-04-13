

solr_core_name = 'indexiuris'

exec(open("./config.py").read())

search_result_rows = 20

solr_results_highlight_tag = 'mark'

solr_field_names ={
"archive" : {"field_title" : "Archive",
	"display" : "brief"},
"title" : {"field_title" : "Title",
	"display" : "brief"},
"url" : {"field_title" : "URL",
	"display" : "brief"},
"type_original" : {"field_title" : "Type of Original Artifact",
	"display" : "brief"},
"type_content" : {"field_title" : "Type of Content",
	"display" : "brief"},
"type_digital" : {"field_title" : "Type of Digital Surrogate",
	"display" : "full"},
"role_ALL" : {"field_title" : "Role",
	"display" : "brief"},
"genre" : {"field_title" : "Genre",
	"display" : "brief"},
"date_human" : {"field_title" : "Human Readable Date",
	"display" : "full"},
"date_digital" : {"field_title" : "Machine Date",
	"display" : "full"},
"provenance" : {"field_title" : "Provenance",
	"display" : "full"},
"place_of_composition" : {"field_title" : "Place of Composition",
	"display" : "full"},
"origin" : {"field_title" : "Origin",
	"display" : "full"},
"shelfmark" : {"field_title" : "Shelfmark",
	"display" : "full"},
"is_freeculture" : {"field_title" : "Freeculture",
	"display" : "full"},
"full_text" : {"field_title" : "Full Text",
	"display" : "full"},
"alternative_title" : {"field_title" : "Alternative Title",
	"display" : "brief"},
"source" : {"field_title" : "Source",
	"display" : "full"},
"text_divisions" : {"field_title" : "Divisions of the Text",
	"display" : "full"},
"discipline" : {"field_title" : "Discipline",
	"display" : "full"},
"language" : {"field_title" : "Language",
	"display" : "full"},
"notes" : {"field_title" : "Notes",
	"display" : "brief"}
}

brief_display_fields = []
for name,info in solr_field_names.items():
	if info['display']=='brief':
		brief_display_fields.append(name)

facet_fields = {
		"archive_facet" : "Archive",
		"type_content_facet" : "Type of Content",
		"type_original_facet" : "Type of Original Artifact",
		"genre_facet" : "Genre",
		"discipline_facet" : "Discipline",
		"language_facet" : "Language"
		#"date"
}

search_fields = set()
for key,val in solr_field_names.items():
	search_fields.add(key)

"""
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
"""

import re, cgi

def strip_html(html):
	#from https://stackoverflow.com/a/19730306

	tag_re = re.compile(r'(<!--.*?-->|<[^>]*>)')

	# Remove well-formed tags, fixing mistakes by legitimate users
	no_tags = tag_re.sub('', html)

	# Clean up anything else by escaping
	ready_for_web = cgi.escape(no_tags)
	return ready_for_web