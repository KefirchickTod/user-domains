<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('domains:dispatch-checks')->everyMinute();
