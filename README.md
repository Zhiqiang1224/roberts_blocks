# ROBOTS

Robots is a project for executing robot arm commands from command file. It is developed under PHP `7.2.3` and composer `1.6.3`.

## Installation

To install the dependencies, please run command:

```bash
php composer install
```

## Execution

Project can be run from command line via:

```bash
php robots.php input.txt
```

`input.txt` should follow this format:

- the first line define the box count
- following lines give the command ('verb box_a prep box_b', like 'move 1 onto 2')
- file ends with "quit" (recommended)

## UnitTest

Unit tests can be run by:

```bash
php phpunit test_command.php
```

Expected outputs:

```txt
OK (5 tests, 51 assertions)
```
