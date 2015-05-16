<?php
MvcRouter::public_connect('{:controller}', array('action' => 'index'));
MvcRouter::public_connect('{:controller}/{:action}');
/* "extra" is seo-friendly text appended to the URL after the id */
MvcRouter::public_connect('{:controller}/{:id:[\d]+}{:extra:(.*)}', array('action' => 'show'));
MvcRouter::public_connect('{:controller}/{:action}/{:id:[\d]+}');
