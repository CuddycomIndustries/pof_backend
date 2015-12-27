<?php
/*
Template Name: Suggestion form
*/

$lang_key = 'fi';
$partio_id = '';
$post_guid = '';

if (   $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST) 
    && array_key_exists('suggetion_name', $_POST) 
    && $_POST['suggetion_name'] != ""
    && array_key_exists('suggetion_title', $_POST) 
    && $_POST['suggetion_title'] != ""
    && array_key_exists('suggetion_content', $_POST) 
    && $_POST['suggetion_content'] != "" ) {

    if (array_key_exists('lang', $_POST) && $_POST['lang'] != "") {
        $lang_key = $_POST['fi'];
    }
    if (array_key_exists('partio_id', $_POST) && $_POST['partio_id'] != "") {
        $partio_id = $_POST['partio_id'];
    }
    if (array_key_exists('post_guid', $_POST) && $_POST['post_guid'] != "") {
        $post_guid = $_POST['post_guid'];
    }
    $wp_error = false;

    $suggestion = array(
	    'post_title'    => trim($_POST['suggetion_title']),
		'post_content'  => $_POST['suggetion_content'],
		'post_type' => 'pof_post_suggestion',
		'post_status'   => 'draft'
	);
	$suggestion_id = wp_insert_post( $suggestion, $wp_error );

    if ($post_guid != '') {

        $args = array(
	        'numberposts' => -1,
	        'posts_per_page' => -1,
	        'post_type' => array('pof_post_task', 'pof_post_taskgroup', 'pof_post_program', 'pof_post_agegroup' ),
	        'meta_key' => 'post_guid',
	        'meta_value' => $post_guid
        );

        $the_query = new WP_Query( $args );

        if( $the_query->have_posts() ) {
	        while ( $the_query->have_posts() ) {
		        $the_query->the_post();
		        $mypost = $the_query->post;
                update_post_meta($suggestion_id, "pof_suggestion_task", $mypost->ID);
	        }
        }
    }

    update_post_meta($suggestion_id, "pof_suggestion_lang", $lang_key);
	update_post_meta($suggestion_id, "pof_suggestion_writer", $_POST['suggetion_name']);
    update_post_meta($suggestion_id, "pof_suggestion_writer_id", $partio_id);

    //TODO: send email

    $emails_str = pof_settings_get_suggestions_emails();

    echo "ff" . wp_mail( $emails_str, "[POF] Uusi vinkki", "Uusi vinkki, http://pof-backend.partio.fi/wp-admin/post.php?post=".$suggestion_id."&action=edit");


    echo pof_taxonomy_translate_get_translation_content("common", "suggestion_form_done", 0, $lang_key);

} else {

    if (array_key_exists('lang', $_GET) && $_GET['lang'] != "") {
        $lang_key = $_GET['lang'];
    }

get_header(); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	            <header class="entry-header">
		            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	            </header><!-- .entry-header -->

			    <div class="entry-content">
		            <form action="" method="POST" class="tips__form">
				        <input class="radius" type="text" name="suggetion_name" placeholder="<?php echo pof_taxonomy_translate_get_translation_content("common", "suggestion_form_name_placeholder", 0, $lang_key, true); ?> *" aria-label="Name" /><br /><br />
				        <input class="radius" type="text" name="suggetion_title" placeholder="<?php echo pof_taxonomy_translate_get_translation_content("common", "suggestion_form_title_placeholder", 0, $lang_key, true); ?> *" aria-label="Title" /><br /><br />
				        <textarea class="radius form-textarea" name="suggetion_content" placeholder="<?php echo pof_taxonomy_translate_get_translation_content("common", "suggestion_form_content_placeholder", 0, $lang_key, true); ?>"></textarea><br /><br />
				        <input class="button radius" type="submit" name="submit-tip" value="<?php echo pof_taxonomy_translate_get_translation_content("common", "suggestion_form_sendbutton", 0, $lang_key, true); ?>" aria-label="Send" />

	    		    </form>


                </div>
            </article>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

<?php } ?>