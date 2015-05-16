<?php
MvcRouter::public_connect('{:controller}', array('action' => 'index'));
MvcRouter::public_connect('{:controller}/{:action}');
MvcRouter::public_connect('{:controller}/{:id:[\d]+}{:extra:(.*)}', array('action' => 'show'));
MvcRouter::public_connect('{:controller}/{:action}/{:id:[\d]+}');
