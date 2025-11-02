<?php

echo password_hash(getenv('MAIL_PASSWORD'), PASSWORD_DEFAULT);