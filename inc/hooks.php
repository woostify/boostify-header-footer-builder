<?php

add_action( 'boostify_hf_seach_form_mobile', 'boostify_hf_search_form', 10 );

// Get header Template
add_action( 'boostify_hf_get_header', 'boostify_hf_header_template', 10 );

// Get Footer Template
add_action( 'boostify_hf_get_footer', 'boostify_hf_footer_template', 10 );