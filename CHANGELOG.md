# Changelog

---

## [3.0.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/3.0.0) - 2018-01-28

### Added

* NSIAF database connection
* Printable supply requests
* Almacenes role

### Fixed

* Departures management
* Login as guest without role
* Left navbar mouse position bug when hover on a subgroup

### Changed

* Splitted roles and permissions on users view

## [2.1.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/2.1.0) - 2018-01-11

### Added

* Ticket print option for bonus procedures

## [2.0.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/2.0.1) - 2018-01-10

### Fixed

* Non editable inputs with some roles on eventual contract form

### Changed

* Reflowed contract form
* Name to shortened with hint on insurance company

## [2.0.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/2.0.0) - 2018-01-05

### Added

* Departures management
* Year bonus procedures
* Dashboard to watch and download basic reports
* Consultant management and procedure's control
* Barcode images on the footer of printed pdfs
* Financiera role

### Fixed

* Get filenames of csv and txt files within axios response headers

### Changed

* Menu left shows on hover
* Splitted left menu into submenus to view contract and payrolls each for eventual and consultant
* Switched user actions to laravel native observers instead middleware

## [1.3.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.1) - 2018-10-24

### Added

* Employee's users dependency

### Fixed

* Username on actions view
* Number format on printable procedures

## [1.3.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.3.0) - 2018-10-22

### Added

* Ldap users administration
* Set and unset roles from users
* Management entity reports
* Printable job certificate

### Changed

* Ldap connection library
* Environment variables
* Procedures table
* Split first name to add second name on Users table

## [1.2.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.2.0) - 2018-09-27

### Added

* Excel management entities printable reports

### Changed

* Migration to split first_name to allow second_name on employees table

## [1.1.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.1.0) - 2018-09-25

### Added

* Restriction on employee deletion when it's signed up on one or more payrolls
* Option add and remove contracts on a created procedure
* Changed language on datepickers

### Changed

* Fixed contract validation, removed retirement date not null when search by end date

## [1.0.1](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.0.1) - 2018-09-21

### Added

* Restriction on procedure creation
* Option to remove procedure
* Option to create procedure with selected year and month
* Activity history

### Changed

* Fixed authorization on CSV and TXT download

## [1.0.0](https://github.com/MUTUAL-DE-SERVICIOS-AL-POLICIA/PVA-RRHH/tree/1.0.0) - 2018-09-17

### Added

* First functional version