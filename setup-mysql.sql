CREATE TABLE services
(
    id INT(9) PRIMARY KEY NOT NULL,
    name VARCHAR(500)
);
INSERT INTO services (id, name) VALUES (1, 'Service 1');
INSERT INTO services (id, name) VALUES (2, 'Service 2');
INSERT INTO services (id, name) VALUES (3, 'Service 3');
INSERT INTO services (id, name) VALUES (4, 'Service 4');
INSERT INTO services (id, name) VALUES (5, 'Service 5');

CREATE TABLE discounts
(
    id INT(9) PRIMARY KEY NOT NULL,
    name VARCHAR(10),
    services VARCHAR(500),
    birthday_before INT(11),
    birthday_after INT(11),
    phone VARCHAR(10),
    gender VARCHAR(5),
    date_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    date_end TIMESTAMP DEFAULT '0000-00-00 00:00:00' NOT NULL
);

INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (1, '25%', '1,3', 604800, 604800, '1', 'M', 1477365852, 1480044252);
INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (2, '30%', '1,4', 604800, 604800, '1', 'F', 1477365852, 1480044252);
INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (3, '20%', '1,2,3', 604800, 604800, '4564$', 'M', 1477365852, null);
INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (4, '15%', '1,4', 604800, 604800, '', '', 1477365852, 1480044252);
INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (5, '10%', '1,2,3', null, 604800, '1', 'F', 148564546, null);
INSERT INTO krtech_test.discounts (id, name, services, birthday_before, birthday_after, phone, gender, date_start, date_end) VALUES (6, '5%', '2,4,6', 604800, null, '1', 'M', 1477365852, null);