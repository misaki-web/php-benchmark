# PHP-benchmark

PHP-benchmark: simple benchmark script to compare PHP code.

Run `benchmark.php -h` for details.

# Example

```
$ ./benchmark.php -i 10000000

Running PHP-benchmark:

  - Number of iterations: 10,000,000
  - Tests file: benchmark-tests.template.php

substr: 1.5785 (item 1,item 2,item 3,item 4)
preg_replace: 3.0360 (item 1,item 2,item 3,item 4)
basename: 1.4448 (item 1,item 2,item 3,item 4)

--------------------------------------------------
basename:     1.4448
substr:       1.5785 (+0.1338) (+9.26%)
preg_replace: 3.0360 (+1.5912) (+110.14%)
--------------------------------------------------
```

If not all results are the same, a warning will be displayed:

```
$ ./benchmark.php -i 10000000

Running PHP-benchmark:

  - Number of iterations: 10,000,000
  - Tests file: benchmark-tests.php

substr: 1.6170 (item 1,item 2,item 3)
preg_replace: 2.4592 (item 1,item 2,item 3,item 4)
basename: 1.4273 (item 1,item 2,item 3,item 4)

--------------------------------------------------
basename:     1.4273 (item 1,item 2,item 3,item 4)
substr:       1.6170 (+0.1897) (+13.29%) (item 1,item 2,item 3)
preg_replace: 2.4592 (+1.0319) (+72.30%) (item 1,item 2,item 3,item 4)
--------------------------------------------------

WARNING: Not all results are the same.
```

# License

Copyright (C) 2023  Misaki F. <https://github.com/misaki-web/php-benchmark>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
