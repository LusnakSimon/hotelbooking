-- Passwords: manager@hotel.com = Manager1!  |  guest@hotel.com = Guest1!
INSERT INTO `users` (email, password_hash, role) VALUES ('manager@hotel.com', '$2y$10$t07aRYNuXT5cSy/m91b.o.nOZQ3NN3AI4c6D6OUTGg53tB5jQm0e.', 'manager');
INSERT INTO `users` (email, password_hash, role) VALUES ('guest@hotel.com', '$2y$10$EO//bB7HwshMyemRMlMC.ew7Nw8C5VSmlSaGwuoqKraMamdcDwm/y', 'guest');

INSERT INTO `hotels` (name, manager_id, location, adress, image_path, description, price) VALUES
('Grand Plaza', 1, 'Bratislava', 'Main St 1', '', 'Central hotel in the heart of the city.', 99.99),
('Sea View Inn', 1, 'Kosice', 'Seaside 5', '', 'A comfortable stay with great views.', 79.50);

INSERT INTO `rooms` (hotel_id, beds) VALUES (1, 2), (1, 1), (2, 2);
