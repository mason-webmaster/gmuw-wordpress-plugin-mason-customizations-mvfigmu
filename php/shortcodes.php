<?php

/**
 * Summary: php file which implements the custom shortcodes
 */


// Add shortcodes on init
add_action('init', function(){

    // Add shortcodes. Add additional shortcodes here as needed.

    // Add example shortcode
    add_shortcode(
        'gmuw_mvfigmu_shortcode', //shortcode label (use as the shortcode on the site)
        'gmuw_mvfigmu_shortcode' //callback function
    );

    // add recorded events shortcode
    add_shortcode(
        'gmuw_mvfigmu_recorded_events', //shortcode label (use as the shortcode on the site)
        'gmuw_mvfigmu_recorded_events' //callback function
    );


});

// Define shortcode callback functions. Add additional shortcode functions here as needed.

// Define example shortcode
function gmuw_mvfigmu_shortcode(){

    // Determine return value
    $content='set what the shortcode will do/say...';

    // Return value
    return $content;

}

// Define recorded events shortcode
function gmuw_mvfigmu_recorded_events(){

    // Initialize return value
    $content='';


    $recorded_events  = get_field('recorded_events');
    $_theme_filter = $_GET['theme_filter'];
    $_series_filter = $_GET['series_filter'];
    //Grabbing choices from series and theme subfiled under Recorded Events ACF Repeater field
    $_series_select_options = get_field_object('field_60caa7f6fa592')["choices"];
    $_theme_select_options = get_field_object('field_60caa805fa593')["choices"];
    $_filter_on = !empty($_theme_filter) || !empty($_series_filter);


    $content.='<div class="rec-events-filter">';
    
    $content.='<form class="rec-event-search--dropdown search" method="GET" action="/recorded-events" name="seriessearch">';
    $content.='<select name="series_filter" class="rec-event--series-select" onchange="seriessearch.submit()">';
    $content.='<option>Select a Series</option>';
    foreach ($_series_select_options as $option):
        $content.='<option value="' . $option . '">' . $option . '</option>';
    endforeach;
    $content.='</select>';
    $content.='</form>';

    $content.='<form class="rec-event-search--dropdown search" method="GET" action="/recorded-events" name="themesearch">';
    $content.='<select name="theme_filter" class="rec-event--theme-select" onchange="themesearch.submit()">';
    $content.='<option>Select a Theme</option>';
    foreach ($_theme_select_options as $option):
        $content.='<option value="' . $option . '">' . $option . '</option>';
    endforeach;
    $content.='</select>';
    $content.='</form>';
    
    $content.='</div>';

    $content.='<div class="rec-events-wrapper">';
    $items_found = 0; 
    foreach ($recorded_events as $rec_event): 
        if($_filter_on) {
            $series = $rec_event["series"];
            $themes = $rec_event["themes"];
            $has_no_category = (empty($series) || empty($themes));
            if ($has_no_category) {
                continue;
            }
            if (!empty($_theme_filter)) {
                if (!empty($themes) and !in_array($_theme_filter, $themes)) {
                    continue;
                }
            } else if(!empty($_series_filter)) {
                if (!empty($series) and !in_array($_series_filter, $series)) {
                    continue;
                }
            }
            $items_found++;
        }
        $content.='<div class="rec-event">';
        if ($rec_event['has_pdf']):
            $content.='<a href="' . $rec_event['pdf'] . '" target="_blank" rel="noopener noreferrer">';
            $content.='<img src="/wp-content/uploads/2021/06/PDF-Icon-New.png" alt="PDF">';
            $content.='<label>' . $rec_event['pdf_title'] . '</label>';
            $content.='</a>';
        endif;
        $content.='<div class="vimeo-modal-btn" data-video-id="' . $rec_event['video_id'] .'" />';
        $content.='<img class="rec-video-screenshot" src="'. $rec_event['thumbnail'] . '" alt="' . $rec_event['title'] .'">';
        $content.='<h4>' . $rec_event['title'] . '</h4>';
        $content.='</div>';
        $content.='</div>';
    endforeach;

    if(!$items_found and $_filter_on):
        $content.='<h2 class="no-items-found-header">No Items Found</h2>';
    endif;

    // Return value
    return $content;

}
