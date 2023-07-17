# Contributing to ApiScout

First and foremost, i'd like to thank you for taking the time to contribute to this project <br />

I know how valuable is everyone's time, and so i'm very gratefull for your decision to invest some of yours into this library <br />

You are awesome for doing so

## Reporting Bugs

If you want to report a bug, you may create and issue about it.
You may do so following those points:
- Check if the bug is not already reported
- A clear title to resume the issue
- A description of how to reproduce the bug

>_NOTE_: Do mind giving as much information as possible

## Pull Requests

You must decide on what branch your changes will be based depending on the nature of the changes.

#### Using the `console`:
``` shell
APP_DEBUG=1 tests/Fixtures/app/bin/console
```
#### Matching coding standards
``` shell
./vendor/bin/php-cs-fixer fix
```

#### Analysing your code with phpstan
``` shell
./vendor/bin/phpstan analyse
```

#### Running the tests
Behat
``` shell
./vendor/bin/behat -f progress
```

Simple-phpunit
``` shell
./vendor/bin/simple-phpunit --stop-on-failure -vvv
```

### Sending a Pull Request
When you send a PR make sur that:
- phpstan is green
- coding style has been applied
- Tests are green (you are welcome to add some)
- You make the PR on the same branch you based your changes on. You should not see commits that you did not make in your PR
- Add a comment when you update a PR with a ping to the maintainer so he will get a notification

The commit messages must follow the Conventional commit specification
