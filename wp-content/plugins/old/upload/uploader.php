add_action('add_attachment', 'create_post');
function create_post( $attach_ID ) {

    $attachment = get_post( $attach_ID );

    $my_post_data = array(
                'post_title' => $attachment->post_title,
                'post_type' => 'post',
                'post_category' => array('0'),
                'post_status' => 'publish'
    );
    $post_id = wp_insert_post( $my_post_data );

    // attach media to post
    wp_update_post( array(
        'ID' => $attach_ID,
        'post_parent' => $post_id,
    ) );

    set_post_thumbnail( $post_id, $attach_ID );

    return $attach_ID;
}