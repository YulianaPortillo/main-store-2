-- MAIN-STORE v2.0 - datos iniciales
-- correr despues de 01_schema.sql en una base vacia

-- perfiles
INSERT INTO profiles (code, name, description) VALUES
('ADMIN', 'Administrador', 'Puede hacer todo'),
('USER', 'Usuario', 'Solo ve el catalogo');

-- menus
INSERT INTO menus (code, name, icon, url, sort_order) VALUES
('dashboard', 'Inicio', 'fas fa-home', 'dashboard.php', 1),
('users', 'Usuarios', 'far fa-user', 'modules/users/index.php', 2),
('profiles', 'Perfiles', NULL, 'modules/profiles/index.php', 3),
('branches', 'Sucursales', NULL, 'modules/branches/index.php', 4),
('stores', 'Tiendas', NULL, 'modules/stores/index.php', 5),
('products', 'Productos', 'fa fa-list', 'modules/products/index.php', 6),
('shop', 'Catálogo', NULL, 'modules/shop/index.php', 7);

-- el admin (1) tiene todos los menus y el usuario (2) solo el catalogo
INSERT INTO profile_menu (profile_id, menu_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7),
(2, 7);

-- usuarios (la clave de los dos es 123456)
INSERT INTO users (profile_id, name, email, password, photo_url) VALUES
(1, 'Yuliana Portillo', 'yuliana@gmail.com', '$2y$10$XslDHmrGJ3MeRoLb8SghKem6pWMscSLazGn.FfS6IQvmdpoCVBCsS', 'https://placecats.com/millie/300/300'),
(2, 'Juan Perez', 'juan@gmail.com', '$2y$10$1fU35Cs.L4KQxePGbne4sOETt8/5j1/cPGzBKA6tEQlAc8Ik6GDDO', 'https://placecats.com/neo/300/300');

-- paises
INSERT INTO countries (name, iso_code) VALUES
('Colombia', 'CO'),
('Ecuador', 'EC'),
('Perú', 'PE');

-- departamentos
INSERT INTO departments (country_id, name) VALUES
(1, 'Antioquia'),
(1, 'Bogotá D.C.'),
(1, 'Cundinamarca'),
(1, 'Valle del Cauca'),
(1, 'Atlántico'),
(2, 'Pichincha'),
(2, 'Guayas'),
(3, 'Lima'),
(3, 'Arequipa');

-- ciudades
INSERT INTO cities (department_id, name) VALUES
(1, 'Medellín'), (1, 'Envigado'), (1, 'Itagüí'), (1, 'Bello'), (1, 'Rionegro'),
(2, 'Bogotá'),
(3, 'Soacha'), (3, 'Chía'), (3, 'Zipaquirá'),
(4, 'Cali'), (4, 'Palmira'), (4, 'Buga'),
(5, 'Barranquilla'), (5, 'Soledad'),
(6, 'Quito'), (6, 'Cayambe'),
(7, 'Guayaquil'), (7, 'Durán'),
(8, 'Lima'), (8, 'Huaral'),
(9, 'Arequipa'), (9, 'Camaná');

-- una sucursal en medellin (ciudad 1)
INSERT INTO branches (city_id, name, address, phone) VALUES
(1, 'Sucursal Centro Medellín', 'Cra. 50 # 50-20', '604 444 0000');

-- dos tiendas
INSERT INTO stores (branch_id, name, description, email, phone) VALUES
(1, 'Tecno Store', 'Tecnología y accesorios', 'tecnostore@gmail.com', '300 000 0001'),
(1, 'Home Store', 'Hogar y cocina', 'homestore@gmail.com', '300 000 0002');

-- productos
INSERT INTO products (store_id, sku, name, description, price, stock, image_url) VALUES
(1, 'TEC-001', 'Audífonos inalámbricos', 'Bluetooth 5.3, 30 h de batería', 189900, 25, 'https://placecats.com/bella/600/400'),
(1, 'TEC-002', 'Teclado mecánico', 'Switches rojos, retroiluminado', 259900, 12, 'https://placecats.com/poppy/600/400'),
(1, 'TEC-003', 'Mouse ergonómico', '6 botones, 4000 DPI', 89900, 40, 'https://placecats.com/louie/600/400'),
(2, 'HOM-001', 'Cafetera de goteo', '12 tazas, filtro permanente', 149900, 10, 'https://placecats.com/millie_neo/600/400'),
(2, 'HOM-002', 'Juego de ollas x5', 'Acero inoxidable', 329900, 8, 'https://placecats.com/neo_banana/600/400'),
(2, 'HOM-003', 'Lámpara de escritorio', 'LED, 3 tonos de luz', 79900, 30, 'https://placecats.com/neo_2/600/400');
