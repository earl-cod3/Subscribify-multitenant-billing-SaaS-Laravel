<?php
if (!function_exists('tenant')) {
  function tenant() { return app('currentTenant'); }
}
