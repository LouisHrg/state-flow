# Changelog

All notable changes to `state-flow` will be documented in this file

## 1.3.1 - 2021-04-12

- Bump php requirements

## 1.3.0 - 2021-03-05

- Use methods as property in your state object

## 1.2.4 - 2020-10-20

- Fix State::canBe breaking when there is no flow for current state

## 1.2.3 - 2020-10-13

- Add doc to artisan command
- Minor changes in State class
- Better example for Nova in doc

## 1.2.2 - 2020-10-05

- Fix setter/getters
- Fix getState booting method
- Update readme

## 1.2.1 - 2020-10-05

- Magic methods can now get array keys
- Fix conflict with setAttribute/getAttribute

## 1.1.1 - 2020-10-04

- Add magic methods to State via parents

## 1.0.1 - 2020-10-03

- Fix State::equal() method

## 1.0.0 - 2020-10-03

- Add tests
- First release

## 0.4.0 - 2020-10-03

- Add artisan command to create states faster

## 0.3.0 - 2020-10-02

- Renaming StateFlow & StateStack to Flow & Stack
- New methods : State::is(), State::equal()
- New methods for Flow : State::canBe(), State::allowedTo()
- Update Readme

## 0.2.0 - 2020-10-02

- you can now defined a default value on creation for states
- flow states with transitions

## 0.1.0 - 2020-10-01

- initial release
- simple states
