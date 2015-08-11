<?php

function get_post_custom_attributes($post) {

	$ret = array();

	switch ($post->post_type) {
		case "pof_post_program":
		
			$languages = get_field("kielet", $post->ID);
		
			$langs = array();
			if (!empty($languages)) {
				foreach ($languages as $language) {
					array_push ($langs, array('name'=>'language', 'attributes' => array('value' => $language)));
				}
			}
			
			$ret = 	array( 
				'name'=>'leaf',
				array(
					'name'=>'languages',
					$langs
				)
			);
			
			break;
	
		case "pof_post_agegroup":
			$ret = 	array( 
				'name'=>'leaf',
				array(
					'name'=>'minAge',
					'attributes' => array(
						'value' => get_field("agegroup_min_age")
					),
				),
				array(
					'name'=>'maxAge',
					'attributes' => array(
						'value' => get_field("agegroup_max_age")
					),
				)
			);
			
			break;
	}

	return $ret;

}

function get_post_tags_XML($post_id) {
	$ret = array(
		'name'=>'tags'
	);
	
	$taitoalueet_tags = wp_get_post_terms($post_id, 'pof_tax_skillarea');
	
	$taitoalueet = array(
		'name'=>'taitoalueet'
	);
	
	foreach ($taitoalueet_tags as $taitoalue_tag) {
		$taitoalue = array(
			'name'=>'taitoalue',
			'value' => $taitoalue_tag->name,
			'attributes' => array(
				'slug' => $taitoalue_tag->slug,
				'id' => $taitoalue_tag->term_taxonomy_id
			),
		);
		array_push($taitoalueet, $taitoalue);
	}
	
	array_push($ret, $taitoalueet);
	
	$suoritus_kesto_tags = wp_get_post_terms($post_id, 'pof_tax_taskduration');
	
	$suoritus_kestot = array(
		'name'=>'task_duration'
	);
	
	foreach ($suoritus_kesto_tags as $suoritus_kesto_tag) {
		$suoritus_kesto = array(
			'name'=>'duration',
			'value' => $suoritus_kesto_tag->name,
			'attributes' => array(
				'slug' => $suoritus_kesto_tag->slug,
				'id' => $suoritus_kesto_tag->term_taxonomy_id
			),
		);
		array_push($suoritus_kestot, $suoritus_kesto);
	}
	
	array_push($ret, $suoritus_kestot);
	
	$suoritus_valmistelu_kesto_tags = wp_get_post_terms($post_id, 'pof_tax_taskpreparationduration');
	
	$suoritus_valmistelu_kestot = array(
		'name'=>'task_preaparation_duration'
	);
	
	foreach ($suoritus_valmistelu_kesto_tags as $suoritus_valmistelu_kesto_tag) {
		$suoritus_valmistelu_kesto = array(
			'name'=>'duration',
			'value' => $suoritus_valmistelu_kesto_tag->name,
			'attributes' => array(
				'slug' => $suoritus_valmistelu_kesto_tag->slug,
				'id' => $suoritus_valmistelu_kesto_tag->term_taxonomy_id
			),
		);
		array_push($suoritus_valmistelu_kestot, $suoritus_valmistelu_kesto);
	}
	
	array_push($ret, $suoritus_valmistelu_kestot);
	
	$tarvike_tags = wp_get_post_terms($post_id, 'pof_tax_equipment');
	
	$tarvikkeet = array(
		'name'=>'equipments'
	);
	
	foreach ($tarvike_tags as $tarvike_tag) {
		$tarvike = array(
			'name'=>'equipment',
			'value' => $tarvike_tag->name,
			'attributes' => array(
				'slug' => $tarvike_tag->slug,
				'id' => $tarvike_tag->term_taxonomy_id
			),
		);
		array_push($tarvikkeet, $tarvike);
	}
	
	array_push($ret, $tarvikkeet);
	
	return $ret;
}

function get_post_images_XML($post_id) {
	$ret = array(
		'name'=>'images'
	);

	$logo = get_field('logo_image', $post_id);

	if ($logo) {
		$logo_arr = array(
			'name'=>'logo',
			'value' => $logo['title'],
			'attributes' => array(
				'mime_type' => $logo['mime_type'],
				'height' => $logo['height'],
				'width' => $logo['width'],
				'url' => $logo['url']
			),
		);
	} else {
		$logo_arr = array(
			'name'=>'logo'
		);
	}

	
	array_push($ret, $logo_arr);
	
	$main_image = get_field('main_image', $post_id);


	if ($main_image) {
		$mainimage_arr = array(
			'name'=>'main_image',
			'value' => $main_image['title'],
			'attributes' => array(
				'mime_type' => $main_image['mime_type'],
				'height' => $main_image['height'],
				'width' => $main_image['width'],
				'url' => $main_image['url']
			),
		);
	} else {
		$mainimage_arr = array(
			'name'=>'main_image'
		);
	}

	
	array_push($ret, $mainimage_arr);

	return $ret;
}

function get_post_additional_content_XML($post_id) {
	$ret = array(
		'name'=>'additional_content'
	);

	$images = simple_fields_fieldgroup("additional_images_fg", $post_id);

	$images_arr = array(
		'name'=>'images'
	);

	if ($images) {
		foreach ($images as $additional_image) {
			if ($additional_image['additional_image']) {
				$image = $additional_image['additional_image'];
				$image_arr = array(
					'name'=>'image',
					'value' => $additional_image['additional_image_text'],
					'attributes' => array(
						'mime_type' => $image['mime'],
						'height' => $image['metadata']['height'],
						'width' => $image['metadata']['width'],
						'url' => $image['url']
					),
				);
				array_push($images_arr, $image_arr);
			}
		}
	}

	array_push($ret, $images_arr);

	$files = simple_fields_fieldgroup("additional_files_fg", $post_id);

	$files_arr = array(
		'name'=>'files'
	);

	if ($files) {
		foreach ($files as $additional_file) {
			if ($additional_file['additional_file']) {
	
				$file = $additional_file['additional_file'];

				$file_arr = array(
					'name'=>'file',
					'value' => $additional_file['additional_file_text'],
					'attributes' => array(
						'mime_type' => $image['mime'],
						'url' => $file['url']
					),
				);
				array_push($files_arr, $file_arr);
			}
		}
	}

	$links = simple_fields_fieldgroup("additional_links_fg", $post_id);

	$links_arr = array(
		'name'=>'links'
	);

	if ($links) {
		foreach ($links as $additional_link) {
			if ($additional_link['additional_link_url']) {
	
				$link = $additional_link['additional_link_url'];

				$link_arr = array(
					'name'=>'link',
					'value' => $additional_link['additional_link_text'],
					'attributes' => array(
						'url' => $link
					),
				);
				array_push($links_arr, $link_arr);
			}
		}
	}

	array_push($ret, $links_arr);

	array_push($ret, $files_arr);


	return $ret;
}

function generate_xml_element( $dom, $data ) {
	$dom->formatOutput = true; // Add whitespace to make easier to read XML
	if ( empty( $data['name'] ) )
		return false;
 
	// Create the element
	$element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
	$element = $dom->createElement( $data['name'], $element_value );
 
	// Add any attributes
	if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
		foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
			$element->setAttribute( $attribute_key, $attribute_value );
		}
	}
 
	// Any other items in the data array should be child elements
	foreach ( $data as $data_key => $child_data ) {
		if ( ! is_numeric( $data_key ) )
			continue;
 
		$child = generate_xml_element( $dom, $child_data );
		if ( $child )
			$element->appendChild( $child );
	}
 
	return $element;
}
 
function getXML($data) {
	$doc = new DOMDocument();
	$doc->formatOutput = true; // Add whitespace to make easier to read XML
	$doc->preserveWhiteSpace = false;
	$child = generate_xml_element( $doc, $data );
	if ( $child )
		$doc->appendChild( $child );
	$outXml = $doc->saveXML();

	$xml = new DOMDocument(); 
	$xml->preserveWhiteSpace = false; 
	$xml->formatOutput = true; 
	$xml->loadXML($outXml); 
	$outXml = $xml->saveXML();
	return $outXml;
}



/** JSON FUNCTIONS */


$available_languages = array('sv', 'en');

function getLastModifiedBy($userId) {
	$tmp = new stdClass();
	$tmp->id = $userId;
	if (!empty($userId)) {
		$tmp->name = get_userdata($userId)->display_name;
	}
	return $tmp;
}


function getJsonItemBaseDetails($jsonItem, $post) {
	global $available_languages;

	$jsonItem->lastModified = $post->post_modified;
	$jsonItem->lastModifiedBy = getLastModifiedBy(get_post_meta( $post->ID, '_edit_last', true));

	$post_guid = get_post_meta( $post->ID, "post_guid", true );

	$jsonItem->guid = $post_guid;

	$lang_obj = new stdClass();
	$lang_obj->lang = 'fi';
	$lang_obj->title = $post->post_title;
	$lang_obj->details = get_site_url() . "/item-json/?postGUID=".$post_guid."&lang=fi";
	$lang_obj->lastModified = $post->post_modified;
	if (empty($jsonItem->languages)) {
		$jsonItem->languages = array();
	}
	array_push($jsonItem->languages, $lang_obj);

	foreach ($available_languages as $available_language) {
		$tmp = get_field("title_".strtolower($available_language), $post->ID);
		if (!empty($tmp)) {
			$lang_obj = new stdClass();
			$lang_obj->lang = $available_language;
			$lang_obj->title = $tmp;
			$lang_obj->details = get_site_url() . "/item-json/?postGUID=".$post_guid."&lang=".$available_language;
			$lang_obj->lastModified = $post->post_modified;
			array_push($jsonItem->languages, $lang_obj);
		}

	}

	return $jsonItem;
}

function getJsonItemBaseDetailsItem($jsonItem, $post) {
	global $available_languages;

	$jsonItem->lastModified = $post->post_modified;
	$jsonItem->lastModifiedBy = getLastModifiedBy(get_post_meta( $post->ID, '_edit_last', true));

	$post_guid = get_post_meta( $post->ID, "post_guid", true );

	$jsonItem->guid = $post_guid;

	$lang_obj = new stdClass();
	$lang_obj->lang = 'fi';
	$lang_obj->details = get_site_url() . "/item-json/?postGUID=".$post_guid."&lang=fi";
	$lang_obj->lastModified = $post->post_modified;
	array_push($jsonItem->languages, $lang_obj);

	foreach ($available_languages as $available_language) {
		$tmp = get_field("title_".strtolower($available_language), $post->ID);
		if (!empty($tmp)) {
			$lang_obj = new stdClass();
			$lang_obj->lang = $available_language;
			$lang_obj->details = get_site_url() . "/item-json/?postGUID=".$post_guid."&lang=".$available_language;
			$lang_obj->lastModified = $post->post_modified;
			array_push($jsonItem->languages, $lang_obj);
		}

	}

	return $jsonItem;
}

function getJsonItemDetailsProgram($jsonItem, $post) {
	$jsonItem->owner = get_field("program_owner", $post->ID);
	$jsonItem->lang = get_field("program_lang", $post->ID);
	return $jsonItem;
}

function getJsonItemDetailsAgegroup($jsonItem, $post) {
	$jsonItem->minAge = get_field("agegroup_min_age", $post->ID);
	$jsonItem->maxAge = get_field("agegroup_max_age", $post->ID);
	$jsonItem->subtaskgroup_term = getJsonSubtaskgroupTerm(get_field("agegroup_subtaskgroup_term", $post->ID));
	return $jsonItem;
}

function getJsonItemDetailsTaskgroup($jsonItem, $post) {
	$jsonItem->additional_tasks_count = get_field("taskgroup_additional_tasks_count", $post->ID);
	$jsonItem->subtask_term = getJsonTaskTerm(get_field("taskgroup_subtask_term", $post->ID));
	return $jsonItem;
}


function getJsonSubtaskgroupTerm($term) {
	$ret = new stdClass();
	$ret->name = $term;
	switch ($term) {
		default:
		case "":
			return null;
			break;
		case "jalki":
			$ret->single = mb_convert_encoding("J�lki","UTF-8", "auto");
			$ret->plural = mb_convert_encoding("J�ljet","UTF-8", "auto");
			break;
		case "kasvatusosio":
			$ret->single = "Kasvatusosio";
			$ret->plural = "Kasvatusosiot";
			break;
		case "ilmansuunta":
			$ret->single = "Ilmansuunta";
			$ret->plural = "Ilmansuunnat";
			break;
		case "taitomerkki":
			$ret->single = "Taitomerkki";
			$ret->plural = "Taitomerkit";
			break;
		case "tarppo":
			$ret->single = "Tarppo";
			$ret->plural = "Tarpot";
			break;
		case "ryhma":
			$ret->single = mb_convert_encoding("Ryhm�","UTF-8", "auto");
			$ret->plural = mb_convert_encoding("Ryhm�t","UTF-8", "auto");
			break;
		case "aktiviteetti":
			$ret->single = "Aktiviteetti";
			$ret->plural = "Aktiviteetit";
			break;
		case "aihe":
			$ret->single = "Aihe";
			$ret->plural = "Aiheet";
			break;
		case "tasku":
			$ret->single = "Tasku";
			$ret->plural = "Taskut";
			break;
		case "rasti":
			$ret->single = "Rasti";
			$ret->plural = "Rastit";
			break;

	}

	return $ret;
}

function getJsonTaskTerm($term) {
	$ret = new stdClass();
	$ret->name = $term;
	switch ($term) {
		default:
		case "":
			return null;
			break;
		case "askel":
			$ret->single = "Askel";
			$ret->plural = "Askeleet";
			break;
		case "aktiviteetti":
			$ret->single = "Aktiviteetti";
			$ret->plural = "Aktiviteetit";
			break;
		case "aktiviteettitaso":
			$ret->single = "Aktiviteettitaso";
			$ret->plural = "Aktiviteettitasot";
			break;
		case "suoritus":
			$ret->single = "Suoritus";
			$ret->plural = "Suoritukset";
			break;
		case "paussi":
			$ret->single = "Paussi";
			$ret->plural = "Paussit";
			break;

	}

	return $ret;
}

$mandatory_task_guids = array();

function getJsonItemDetailsTask($jsonItem, $post) {
	global $available_languages;
	global $mandatory_task_guids;

	if (get_field("task_mandatory", $post->ID)) {
		array_push($mandatory_task_guids, get_post_meta( $post->ID, "post_guid", true ));
	}
/*
	$jsonItem->mandatory = get_field("task_mandatory", $post->ID);
	$jsonItem->mandatory_seascouts = get_field("task_mandatory_seascouts", $post->ID);

	$groupsize = get_field("task_groupsize", $post->ID);

	if (empty($groupsize)) {
		$jsonItem->groupsize = array('group');
	} else {
		$jsonItem->groupsize = $groupsize;
	}

	$place_of_performance = get_field("task_place_of_performance", $post->ID);

	if (empty($place_of_performance)) {
		$jsonItem->place_of_performance = array('meeting_place');
	} else {
		$jsonItem->place_of_performance = $place_of_performance;
	}*/

	$post_guid = get_post_meta( $post->ID, "post_guid", true );

	$lang_obj = new stdClass();
	$lang_obj->lang = 'fi';
	$lang_obj->details = get_site_url() . "/item-json-vinkit/?postGUID=".$post_guid."&lang=fi";
	$lang_obj->lastModified = "2015-03-26 18:15:34";
	array_push($jsonItem->suggestions_details, $lang_obj);

	foreach ($available_languages as $available_language) {
		$tmp = get_field("title_".strtolower($available_language), $post->ID);
		if (!empty($tmp)) {
			$lang_obj = new stdClass();
			$lang_obj->lang = $available_language;
			$lang_obj->details = get_site_url() . "/item-json-vinkit/?postGUID=".$post_guid."&lang=".$available_language;
			$lang_obj->lastModified = "2015-03-26 18:15:34";
			array_push($jsonItem->suggestions_details, $lang_obj);
		}

	}

	return $jsonItem;
} 



function get_post_tags_JSON($post_id, $agegroup_id, $lang) {
	$ret = new stdClass();


	$pakollisuus = array();

	if (get_field("task_mandatory", $post_id)) {
		$pakollinen = new stdClass();
		$tmp_name = pof_taxonomy_translate_get_translation('mandatory', 'mandatory', $agegroup_id, $lang, true);

		if (!empty($tmp_name)) {
			$pakollinen->name = $tmp_name[0]->content;
		} else {
			$pakollinen->name = 'Pakollinen';
		}
		$pakollinen->slug = 'mandatory';
		$icon = pof_taxonomy_icons_get_icon('mandatory', 'mandatory', $agegroup_id, true);

		if (!empty($icon)) {
			$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
			if (!empty($icon_src)) {
				$pakollinen->icon = $icon_src[0];
			}
		}
		array_push($pakollisuus, $pakollinen);
	}

	if (get_field("task_mandatory_seascouts", $post_id)) {
		$pakollinen = new stdClass();
		$tmp_name = pof_taxonomy_translate_get_translation('mandatory', 'mandatory_seascouts', $agegroup_id, $lang, true);

		if (!empty($tmp_name)) {
			$pakollinen->name = $tmp_name[0]->content;
		} else {
			$pakollinen->name = 'Pakollinen meripartiolaisille';
		}
		$pakollinen->slug = 'mandatory_seascouts';
		$icon = pof_taxonomy_icons_get_icon('mandatory', 'mandatory_seascouts', $agegroup_id, true);

		if (!empty($icon)) {
			$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
			if (!empty($icon_src)) {
				$pakollinen->icon = $icon_src[0];
			}
		}
		array_push($pakollisuus, $pakollinen);
	}


	if (count($pakollisuus) > 0) {
		$ret->pakollisuus = $pakollisuus;
	} else {
		$pakollinen = new stdClass();
		$tmp_name = pof_taxonomy_translate_get_translation('mandatory', 'not_mandatory', $agegroup_id, $lang, true);

		if (!empty($tmp_name)) {
			$pakollinen->name = $tmp_name[0]->content;
		} else {
			$pakollinen->name = 'Ei pakollinen';
		}
		$pakollinen->slug = 'not_mandatory';
		$icon = pof_taxonomy_icons_get_icon('mandatory', 'not_mandatory', $agegroup_id, true);

		if (!empty($icon)) {
			$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
			if (!empty($icon_src)) {
				$pakollinen->icon = $icon_src[0];
			}
		}
		array_push($pakollisuus, $pakollinen);
		$ret->pakollisuus = $pakollisuus;
	}

	$groupsizes = get_field("task_groupsize", $post_id);

	$ret_groupsizes = array();

	if (empty($groupsizes)) {
		$gropsize = new stdClass();
		
		$tmp_name = pof_taxonomy_translate_get_translation('groupsize', 'group', $agegroup_id, $lang, true);

		if (!empty($tmp_name)) {
			$gropsize->name = $tmp_name[0]->content;
		} else {
			$gropsize->name = 'Laumassa';
		}
		$gropsize->name = 'Laumassa';
		$gropsize->slug = 'group';
		
		array_push($ret_groupsizes, $gropsize);
	
	} else {
		foreach ($groupsizes as $tmp_groupsize) {
			$gropsize = new stdClass();

			$icon = pof_taxonomy_icons_get_icon('groupsize', $tmp_groupsize, $agegroup_id, true);

			if (!empty($icon)) {
				$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
				if (!empty($icon_src)) {
					$groupsize->icon = $icon_src[0];
				}
			}

			$tmp_name = pof_taxonomy_translate_get_translation('groupsize', $tmp_groupsize, $agegroup_id, $lang, true);


			if (!empty($tmp_name)) {
				$gropsize->name = $tmp_name[0]->content;
			} else {
				switch ($tmp_groupsize) {
					default:
						$gropsize->name = $tmp_groupsize;
						break;
					case "one":
						$gropsize->name = 'Yksin';
						break;
					case "two":
						$gropsize->name = 'Kaksin';
						break;
					case "few":
						$gropsize->name = 'Muutama';
						break;
					case "group":
						$gropsize->name = 'Laumassa';
						break;
					case "big":
						$gropsize->name = 'Isommassa porukassa';
						break;
				}
			}

			$gropsize->slug = $tmp_groupsize;
		
			array_push($ret_groupsizes, $gropsize);

		}
	}

	if (count($ret_groupsizes) > 0) {
		$ret->ryhmakoko = $ret_groupsizes;
	}	

	$place_of_performance = get_field("task_place_of_performance", $post_id);

	$ret_places = array();

	if (empty($place_of_performance)) {
		$place = new stdClass();
		$tmp_name = pof_taxonomy_translate_get_translation('place_of_performance', 'meeting_place', $agegroup_id, $lang, true);

		if (!empty($tmp_name)) {
			$place->name = $tmp_name[0]->content;
		} else {
			$place->name = 'Kolo';
		}
		$place->slug = 'meeting_place';
		
		array_push($ret_places, $place);
	
	} else {
		foreach ($place_of_performance as $tmp_place) {
			$place = new stdClass();
			$icon = pof_taxonomy_icons_get_icon('place_of_performance',$tmp_place, $agegroup_id, true);

			if (!empty($icon)) {
				$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
				if (!empty($icon_src)) {
					$place->icon = $icon_src[0];
				}
			}

			$tmp_name = pof_taxonomy_translate_get_translation('place_of_performance', $tmp_place, $agegroup_id, $lang, true);

			if (!empty($tmp_name)) {
				$place->name = $tmp_name[0]->content;
			} else {

				switch ($tmp_place) {
					default:
						$place->name = $tmp_place;
						break;
					case "meeting_place":
						$place->name = 'Kolo';
						break;
					case "hike":
						$place->name = 'Retki';
						break;
					case "camp":
						$place->name = 'Leiri';
						break;
					case "boat":
						$place->name = 'Vene';
						break;
					case "other":
						$place->name = 'Muu';
						break;
				}
			}

			$place->slug = $tmp_place;
		
			array_push($ret_places, $place);

		}
	}

	if (count($ret_places) > 0) {
		$ret->paikka = $ret_places;
	}	

	$taitoalueet_tags = wp_get_post_terms($post_id, 'pof_tax_skillarea');
	
	$taitoalueet = array();

	foreach ($taitoalueet_tags as $taitoalue_tag) {
		$taitoalue = new stdClass();
		$tmp_name = pof_taxonomy_translate_get_translation('skillarea', $taitoalue_tag->slug, $agegroup_id, $lang, true);
		if (!empty($tmp_name)) {
			$taitoalue->name = $tmp_name[0]->content;
		} else {
			$taitoalue->name = $taitoalue_tag->name;
		}
		$taitoalue->slug = $taitoalue_tag->slug;

		array_push($taitoalueet, $taitoalue);
	}
	if (count($taitoalueet) > 0) {
		$ret->taitoalueet = $taitoalueet;
	}

	$suoritus_kesto_tmp = get_field("task_duration", $post_id);
	if ($suoritus_kesto_tmp) {
		$suoritus_kesto = new stdClass();
		$suoritus_kesto->name = $suoritus_kesto_tmp;
		$suoritus_kesto->slug = $suoritus_kesto_tmp;
		$icon = pof_taxonomy_icons_get_icon('taskduration', $suoritus_kesto_tmp, $agegroup_id, true);

		if (!empty($icon)) {
			$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
			if (!empty($icon_src)) {
				$suoritus_kesto->icon = $icon_src[0];
			}
		}
		$ret->suoritus_kesto = $suoritus_kesto;
	}

	$suoritus_valmistelu_kesto_tmp = get_field("task_preparationduration", $post_id);
	if ($suoritus_valmistelu_kesto_tmp) {
		$suoritus_valmistelu_kesto = new stdClass();
		$suoritus_valmistelu_kesto->name = $suoritus_valmistelu_kesto_tmp;
		$suoritus_valmistelu_kesto->slug = $suoritus_valmistelu_kesto_tmp;
		$icon = pof_taxonomy_icons_get_icon('taskpreaparationduration', $suoritus_valmistelu_kesto_tmp, $agegroup_id, true);

		if (!empty($icon)) {
			$icon_src = wp_get_attachment_image_src($icon[0]->attachment_id);
			if (!empty($icon_src)) {
				$suoritus_valmistelu_kesto->icon = $icon_src[0];
			}
		}
		$ret->suoritus_valmistelu_kesto = $suoritus_valmistelu_kesto;
	}
	
	$tarvike_tags = wp_get_post_terms($post_id, 'pof_tax_equipment');
	
	$tarvikkeet = array();
	
	foreach ($tarvike_tags as $tarvike_tag) {
		$tarvike = new stdClass();

		$tmp_name = pof_taxonomy_translate_get_translation('equpment', $tarvike_tag->slug, $agegroup_id, $lang, true);
		if (!empty($tmp_name)) {
			$tarvike->name = $tmp_name[0]->content;
		} else {
			$tarvike->name = $tarvike_tag->name;
		}
		$tarvike->slug = $tarvike_tag->slug;
		array_push($tarvikkeet, $tarvike);
	}
	if (count($tarvikkeet)) {
		$ret->tarvikkeet = $tarvikkeet;
	}

	return $ret;
}

function get_post_images_JSON($post_id) {
	$ret = new stdClass();
	$ret->logo = new stdClass();
	$ret->main_image = new stdClass();

	$logo = get_field('logo_image', $post_id);
	if ($logo) {

		$ret->logo->type = 'logo';
		$ret->logo->title = $logo['title'];
		$ret->logo->mime_type = $logo['mime_type'];
		$ret->logo->height = $logo['height'];
		$ret->logo->width = $logo['width'];
		$ret->logo->url = $logo['url'];

		if (!empty($logo['sizes'])) {
			if (!empty($logo['sizes']['thumbnail'])) {		
				$thumbnail = new stdClass();
				$thumbnail->height = $logo['sizes']['thumbnail-height'];
				$thumbnail->width = $logo['sizes']['thumbnail-width'];
				$thumbnail->url = $logo['sizes']['thumbnail'];
				$ret->logo->thumbnail = $thumbnail;
			}
			if (!empty($logo['sizes']['medium'])) {		
				$medium = new stdClass();
				$medium->height = $logo['sizes']['medium-height'];
				$medium->width = $logo['sizes']['medium-width'];
				$medium->url = $logo['sizes']['medium'];
				$ret->logo->medium = $medium;
			}
			if (!empty($logo['sizes']['large'])) {		
				$large = new stdClass();
				$large->height = $logo['sizes']['large-height'];
				$large->width = $logo['sizes']['large-width'];
				$large->url = $logo['sizes']['large'];
				$ret->logo->large = $large;
			}
		}

	}

	$main_image = get_field('main_image', $post_id);


	if ($main_image) {
		$ret->main_image->type = 'main_image';
		$ret->main_image->title = $main_image['title'];
		$ret->main_image->mime_type = $main_image['mime_type'];
		$ret->main_image->height = $main_image['height'];
		$ret->main_image->width = $main_image['width'];
		$ret->main_image->url = $main_image['url'];

		if (!empty($main_image['sizes'])) {
			if (!empty($main_image['sizes']['thumbnail'])) {		
				$thumbnail = new stdClass();
				$thumbnail->height = $main_image['sizes']['thumbnail-height'];
				$thumbnail->width = $main_image['sizes']['thumbnail-width'];
				$thumbnail->url = $main_image['sizes']['thumbnail'];
				$ret->main_image->thumbnail = $thumbnail;
			}
			if (!empty($main_image['sizes']['medium'])) {		
				$medium = new stdClass();
				$medium->height = $main_image['sizes']['medium-height'];
				$medium->width = $main_image['sizes']['medium-width'];
				$medium->url = $main_image['sizes']['medium'];
				$ret->main_image->medium = $medium;
			}
			if (!empty($main_image['sizes']['large'])) {		
				$large = new stdClass();
				$large->height = $main_image['sizes']['large-height'];
				$large->width = $main_image['sizes']['large-width'];
				$large->url = $main_image['sizes']['large'];
				$ret->main_image->large = $large;
			}
		}

	}

	return $ret;
}

function get_post_additional_content_JSON($post_id) {
	$ret = new stdClass();

	$images = simple_fields_fieldgroup("additional_images_fg", $post_id);

	$images_arr = array();

	if ($images) {
		foreach ($images as $additional_image) {
			if ($additional_image['additional_image']) {
				$image = $additional_image['additional_image'];

				$image_obj = new stdClass();
				$image_obj->description = $additional_image['additional_image_text'];
				$image_obj->mime_type = $image['mime'];
				$image_obj->height = $image['metadata']['height'];
				$image_obj->width = $image['metadata']['width'];
				$image_obj->url = $image['url'];


				if (!empty($image['image_src'])) {
					if (!empty($image['image_src']['thumbnail'])) {		
						$thumbnail = new stdClass();
						$thumbnail->height = $image['image_src']['thumbnail'][1];
						$thumbnail->width = $image['image_src']['thumbnail'][2];
						$thumbnail->url = $image['image_src']['thumbnail'][0];
						$image_obj->thumbnail = $thumbnail;
					}
					if (!empty($image['image_src']['medium'])) {		
						$medium = new stdClass();
						$medium->height = $image['image_src']['medium'][1];
						$medium->width = $image['image_src']['medium'][2];
						$medium->url = $image['image_src']['medium'][0];
						$image_obj->medium = $medium;
					}
					if (!empty($image['image_src']['large'])) {		
						$large = new stdClass();
						$large->height = $image['image_src']['large'][1];
						$large->width = $image['image_src']['large'][2];
						$large->url = $image['image_src']['large'][0];
						$image_obj->large = $large;
					}
				}


				array_push($images_arr, $image_obj);
			}
		}
	}

	if (count($images_arr) > 0) {
		$ret->images = $images_arr;
	}

	$files = simple_fields_fieldgroup("additional_files_fg", $post_id);

	$files_arr = array();

	if ($files) {
		foreach ($files as $additional_file) {
			if ($additional_file['additional_file']) {
	
				$file = $additional_file['additional_file'];

				$file_obj = new stdClass();
				$file_obj->description = $additional_file['additional_file_text'];
				$file_obj->mime_type = $image['mime'];
				$file_obj->url = $file['url'];
				array_push($files_arr, $file_obj);
			}
		}
	}

	if (count($files_arr) > 0) {
		$ret->files = $files_arr;
	}

	$links = simple_fields_fieldgroup("additional_links_fg", $post_id);

	$links_arr = array();

	if ($links) {
		foreach ($links as $additional_link) {
			if ($additional_link['additional_link_url']) {
	
				$link = $additional_link['additional_link_url'];

				$link_obj = new stdClass();
				$link_obj->description = $additional_link['additional_link_text'];
				$link_obj->url = $link;
				array_push($links_arr, $link_obj);
			}
		}
	}

	if (count($links_arr) > 0) {
		$ret->links = $links_arr;
	}

	return $ret;
}


function getMandatoryTasksForTaskGroup($parent_id) {
	$args = array(
		'numberposts' => -1,
		'post_type' => 'pof_post_task',
		'meta_key' => 'suoritepaketti',
		'meta_value' => $parent_id
	);

	$the_query = new WP_Query( $args );

	$ret = new stdClass();

	$ret->ids = array();
	$ret->hashes = array();

	if( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			if (get_field("task_mandatory", $the_query->post->ID)) {
				array_push($ret->ids, $the_query->post->ID);
				array_push($ret->hashes, wp_hash($the_query->post->ID));
			}
		}
	}

	return $ret;
}

function pof_get_parent_tree($post_item, $tree_array) {
	$post_type = str_replace('pof_post_', '', $post_item->post_type);
	$post_id = $post_item->ID;

	switch ($post_type) {
		case "program":
//			$post_class = $classProgram;
		break;
		case "agegroup":
			$ohjelma_id = get_post_meta( $post_id, "suoritusohjelma", true );
			if (!is_null($ohjelma_id) && $ohjelma_id != "null" && $ohjelma_id != "" ) {
				$ohjelma = get_post($ohjelma_id);
				array_push($tree_array, $ohjelma);
//				$tree_array = pof_get_parent_tree($ohjelma, $tree_array);
			}
		break;
		case "taskgroup":
			$taskgroup_id = get_post_meta( $post_id, "suoritepaketti", true );
			if (!is_null($taskgroup_id) && $taskgroup_id != "null" && $taskgroup_id != "") {
				$taskgroup = get_post($taskgroup_id);
				array_push($tree_array, $taskgroup);
				$tree_array = pof_get_parent_tree($taskgroup, $tree_array);
			} else {
				$ikaryhma_id = get_post_meta( $post_id, "ikakausi", true );
				if (!is_null($ikaryhma_id) && $ikaryhma_id != "null") {
					$ikaryhma = get_post($ikaryhma_id);
					array_push($tree_array, $ikaryhma);
					$tree_array = pof_get_parent_tree($ikaryhma, $tree_array);
				}
			}
		break;
		case "task":
			$taskgroup_id = get_post_meta( $post_id, "suoritepaketti", true );

			if (!is_null($taskgroup_id) && $taskgroup_id != "null") {
				$taskgroup = get_post($taskgroup_id);
				array_push($tree_array, $taskgroup);
				$tree_array = pof_get_parent_tree($taskgroup, $tree_array);
			}
		break;
	}


	return $tree_array;
}


function pof_save_post_hook($post_id) {
	// If this is a revision, get real post ID
	if ( $parent_id = wp_is_post_revision( $post_id ) ) {
		$post_id = $parent_id;
	}


	$post_guid = get_post_meta( $post_id, "post_guid", true );

	if (!$post_guid) {
		remove_action( 'save_post', 'pof_save_post_hook' );
		update_post_meta($post_id, "post_guid", wp_hash($post_id));
		add_action( 'save_post', 'pof_save_post_hook' );
	}

//	$tmp_post = get_post($post_id);
}


add_action( 'save_post', 'pof_save_post_hook' );

function pof_item_guid_add_meta_box() {

	$screens = array('pof_post_task', 'pof_post_taskgroup', 'pof_post_program', 'pof_post_agegroup' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'pof_item_guid_add_meta_box_sectionid',
			__( 'GUID', 'pof' ),
			'pof_item_guid_add_meta_box_callback',
			$screen, 'side', 'high'
		);
	}
}

function pof_item_guid_add_meta_box_callback($post) {
	echo get_post_meta( $post->ID, "post_guid", true );
}

add_action( 'add_meta_boxes', 'pof_item_guid_add_meta_box' );


function pof_item_task_parenttree_meta_box() {

	$screens = array('pof_post_task');

	foreach ( $screens as $screen ) {

		add_meta_box(
			'pof_item_task_parenttree_meta_box_sectionid',
			__( 'Parent tree', 'pof' ),
			'pof_item_task_parenttree_meta_box_callback',
			$screen, 'side', 'high'
		);
	}
}

function pof_item_task_parenttree_meta_box_callback($post) {
	$tree_array = array();
	array_push($tree_array, $post);
	$tree_array = array_reverse(pof_get_parent_tree($post, $tree_array));

	foreach ($tree_array as $tree_key => $tree_post) {
		echo "<ul style=\"margin-left: 10px; list-style-type: round;\">";
		echo "<li>";
		echo "<a href=\"/wp-admin/post.php?post=" . $tree_post->ID . "&action=edit\" target=\"_blank\">" . $tree_post->post_title . "</a>";

	}

	foreach ($tree_array as $tree_post) {
		echo "</li>";
		echo "</ul>";
	}
}

add_action( 'add_meta_boxes', 'pof_item_task_parenttree_meta_box' );

function pof_output_parents_arr_json($tree_array) {
	$ret = array();
	foreach ($tree_array as $tree_item) {
		$tmp = new stdClass();
		$tmp->type = str_replace('pof_post_', '',$tree_item->post_type);
		$tmp->title = $tree_item->post_title;
		$tmp = getJsonItemBaseDetails($tmp, $tree_item);
		array_push($ret, $tmp);
	}

	return $ret;
}

function pof_get_agegroup_from_tree_arr($tree_array) {
	$agegropup = null;

	foreach ($tree_array as $tree_item) {
		if (empty($tree_item)) {
			continue;
		}
		if ($tree_item->post_type == 'pof_post_agegroup') {
			$agegroup = $tree_item;
			break;
		}
	}

	return $agegroup;
}