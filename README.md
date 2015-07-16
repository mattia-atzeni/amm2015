# Progetto di Amministrazione di Sistema 2015
## Autore: Mattia Atzeni

## Descrizione dell'applicazione

L'applicazione fornisce all'utente un punto unico di accesso a corsi online erogati
da siti differenti.
In particolare, gli amministratori possono creare e rimuovere i corsi, mentre gli utenti
possono ricercare i corsi a cui sono interessati, iscriversi e abbandonarli.
Quando un amministratore rimuove un corso, tale corso non sarà più accessibile neanche agli utenti iscritti.
Per ogni corso, l'applicazione tiene traccia dei seguenti attributi:

* Nome
* Link
* Categoria
* Host
* Utente Proprietario

Ad ogni utente vengono associati:

* Nome
* Cognome
* Email
* Username
* Password

## Requisiti rispettati

* Utilizzo HTML e CSS
* Utilizzo di PHP e MySQL
* Utilizzo del pattern MVC
* Due ruoli
* Transazione: metodo removeCourseById della classe CourseFactory
* La funzionalità ajax è stata implementata per la ricerca dei corsi

## Accesso al sistema

### Account Amministratore</strong>
* Username: admin
* Password: moocca
### Account Utente
* Username: user
* Password: moocca
