-- Name : Deep 
-- Date : 7/9/2022
-- Course Code : webd3201 
CREATE EXTENSION IF NOT EXISTS pgcrypto;

/*Drop the child first and then drop the parent*/
DROP TABLE IF EXISTS users CASCADE;

DROP SEQUENCE IF EXISTS users_id_sequence CASCADE;

CREATE SEQUENCE users_id_sequence START 1000;

DROP TABLE IF EXISTS clients CASCADE;

/*Another table for clients*/
CREATE TABLE users(
    id INT PRIMARY KEY DEFAULT nextval('users_id_sequence'),
    emailAddress VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstName VARCHAR(128) NOT NULL,
    lastName VARCHAR(128) NOT NULL,
    lastAccess TIMESTAMP,
    enrollDate TIMESTAMP,
    phoneExtension VARCHAR(128),
    Type VARCHAR(1)
);

INSERT INTO
    users (
        emailAddress,
        password,
        firstName,
        lastName,
        Type
    )
VALUES
    (
        'dsbharaj0@gmail.com',
        crypt('100819279', gen_salt('bf')),
        'Deep',
        'Bharaj',
        's'
    );

INSERT INTO
    users (
        emailAddress,
        password,
        firstName,
        lastName,
        Type
    )
VALUES
    (
        'mike@gmail.com',
        crypt('123', gen_salt('bf')),
        'Mike',
        'Tyson',
        'a'
    );

INSERT INTO
    users (
        emailAddress,
        password,
        firstName,
        lastName,
        Type
    )
VALUES
    (
        'gida@gmail.com',
        crypt('123456789', gen_salt('bf')),
        'Gida',
        'Haider',
        'a'
    );

DROP SEQUENCE IF EXISTS clients_id_seq CASCADE;

CREATE SEQUENCE clients_id_seq START 5000;

CREATE TABLE clients (
    Id INT PRIMARY KEY DEFAULT nextval('clients_id_seq'),
    emailAddress VARCHAR(255) UNIQUE,
    firstName VARCHAR(128),
    lastName VARCHAR(128),
    phoneNumber BIGINT,
    phoneExtension INT,
    logoPath VARCHAR(255),
    sales_id INT NOT NULL,
    FOREIGN KEY (sales_id) references users(Id)
);

INSERT INTO
    clients (
        emailAddress,
        firstName,
        lastName,
        phoneNumber,
        phoneExtension,
        sales_id
    )
VALUES
    (
        'ty@gmail.com',
        'Tyler',
        'Yosung',
        9323108259,
        11,
        1000
    );

INSERT INTO
    clients (
        emailAddress,
        firstName,
        lastName,
        phoneNumber,
        phoneExtension,
        sales_id
    )
VALUES
    (
        'ds@gmail.com',
        'Deep',
        'Singh',
        9594472584,
        11,
        1001
    );

INSERT INTO
    clients (
        emailAddress,
        firstName,
        lastName,
        phoneNumber,
        phoneExtension,
        sales_id
    )
VALUES
    (
        'fr@gmail.com',
        'Fqroq',
        'Liad',
        7066502110,
        11,
        1002
    );

SELECT
    *
FROM
    users;

SELECT
    *
FROM
    clients;