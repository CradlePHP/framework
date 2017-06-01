<?php //-->

use Cradle\Http\HttpDispatcher;

return function ($code = null, array $headers = array(), string $body = '') {
    if (is_int($code)) {
        http_response_code($code);
    }

    foreach ($headers as $name => $value) {
        if (!$value) {
            header($name);
            continue;
        }

        header($name.':'.$value);
    }

    //there can be things echoed already
    //let's capture it so we can pass it later
    $trailer = ob_get_contents();

    //make sure nothing is already in the buffer
    ob_end_clean();

    //close the connection
    header(HttpDispatcher::HEADER_CONNECTION_CLOSE);

    //add content encoding only if there is none set
    if (!isset($headers['Content-Encoding'])
        && !isset($headers['content-encoding'])
    ) {
        header(HttpDispatcher::HEADER_CONTENT_ENCODING);
    }

    //if they were waiting for a response
    //and they hit stop, it should mean that
    //we should also stop
    ignore_user_abort(false);

    //startup the buffer again
    ob_start();

    //business as usual
    echo $trailer;
    echo $body;

    //send the content size
    $size = ob_get_length();
    header(sprintf(HttpDispatcher::HEADER_CONTENT_LENGTH, $size));

    //send out the buffer
    //clean up the buffer
    //3 times a charm
    ob_end_flush();
    flush();

    if (ob_get_length()) {
        ob_end_clean();
    }

    //sorry no more sessions
    session_write_close();
};
