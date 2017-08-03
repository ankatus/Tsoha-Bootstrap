-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Account (
	account_id SERIAL PRIMARY KEY,
	account_name varchar(50) NOT NULL
);

CREATE TABLE Game (
	game_id SERIAL PRIMARY KEY,
	game_name varchar(50) NOT NULL
);

CREATE TABLE Account_game (
	account_id INTEGER REFERENCES Account (account_id),
	game_id INTEGER REFERENCES Game (game_id)
);