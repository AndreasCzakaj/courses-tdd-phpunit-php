<?php

// The database of the user self service: a SQLite file.

declare(strict_types=1);

return 'sqlite:' . (getenv('USS_DB') ?: sys_get_temp_dir() . '/uss.sqlite');
