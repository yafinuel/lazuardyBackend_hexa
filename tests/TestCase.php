<?php

namespace Tests;

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;
}