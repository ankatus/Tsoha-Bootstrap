-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Account (
	account_id SERIAL PRIMARY KEY,
	account_name varchar NOT NULL,
	account_password varchar NOT NULL
);

CREATE TABLE Game (
	game_id SERIAL PRIMARY KEY,
	game_name varchar NOT NULL,
	game_url varchar NOT NULL,
	game_desc varchar NOT NULL
);

CREATE TABLE Account_game (
	account_id INTEGER REFERENCES Account (account_id),
	game_id INTEGER REFERENCES Game (game_id)
);

CREATE TABLE Friend (
	account_1_id INTEGER REFERENCES Account (account_id),
	account_2_id INTEGER REFERENCES Account (account_id)
);