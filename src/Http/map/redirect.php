<?php //-->

use Cradle\Http\HttpDispatcher;

return function (string $path, bool $force = false) {
    if ($force) {
        header('Location: ' . $path);
        exit;
    }

    //if they were waiting for a response
    //and they hit stop, it should mean that
    //we should also stop
    ignore_user_abort(false);

    header('Location: ' . $path);

    //close the connection
    header(HttpDispatcher::HEADER_CONNECTION_CLOSE);

    //add content encoding
    header(HttpDispatcher::HEADER_CONTENT_ENCODING);

    //add 0 size
    header(sprintf(HttpDispatcher::HEADER_CONTENT_LENGTH, 0));

    //clean up the buffer
    //2 times a charm
    flush();
    ob_flush();

    //sorry no more sessions
    session_write_close();
};
