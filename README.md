# apache-compat
Some apache function that are not available in default php5.

These following functions are implemented:

    - apache_request_headers
    - apache_response_headers
    - getallheaders
    - apache_child_terminate
    - apache_get_modules (based on get_loaded_extensions)
    - apache_get_version (always false)
    - apache_getenv ($walk_to_top does not do anything)
