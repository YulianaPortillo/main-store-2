-- MAIN-STORE v2.0 - tablas
-- base de datos: mainstore_v2

-- funcion para actualizar updated_at
CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at := NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- perfiles
CREATE TABLE profiles (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(80) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- menus del sidebar
CREATE TABLE menus (
    id SERIAL PRIMARY KEY,
    parent_id INT REFERENCES menus(id),
    code VARCHAR(40) NOT NULL UNIQUE,
    name VARCHAR(80) NOT NULL,
    icon VARCHAR(60),
    url VARCHAR(150) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- que menus tiene cada perfil
CREATE TABLE profile_menu (
    id SERIAL PRIMARY KEY,
    profile_id INT NOT NULL REFERENCES profiles(id),
    menu_id INT NOT NULL REFERENCES menus(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    UNIQUE (profile_id, menu_id)
);

-- usuarios
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    profile_id INT NOT NULL REFERENCES profiles(id),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo_url VARCHAR(500),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- paises
CREATE TABLE countries (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    iso_code CHAR(2) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- departamentos
CREATE TABLE departments (
    id SERIAL PRIMARY KEY,
    country_id INT NOT NULL REFERENCES countries(id),
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- ciudades
CREATE TABLE cities (
    id SERIAL PRIMARY KEY,
    department_id INT NOT NULL REFERENCES departments(id),
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- sucursales (solo guarda la ciudad)
CREATE TABLE branches (
    id SERIAL PRIMARY KEY,
    city_id INT NOT NULL REFERENCES cities(id),
    name VARCHAR(120) NOT NULL,
    address VARCHAR(200) NOT NULL,
    phone VARCHAR(30),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- tiendas
CREATE TABLE stores (
    id SERIAL PRIMARY KEY,
    branch_id INT NOT NULL REFERENCES branches(id),
    name VARCHAR(120) NOT NULL,
    description VARCHAR(255),
    email VARCHAR(120),
    phone VARCHAR(30),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- productos
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    store_id INT NOT NULL REFERENCES stores(id),
    sku VARCHAR(40) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price NUMERIC(12,2) NOT NULL CHECK (price >= 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    image_url VARCHAR(500),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

-- triggers de updated_at
CREATE TRIGGER trg_profiles_upd BEFORE UPDATE ON profiles FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_menus_upd BEFORE UPDATE ON menus FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_profile_menu_upd BEFORE UPDATE ON profile_menu FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_users_upd BEFORE UPDATE ON users FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_countries_upd BEFORE UPDATE ON countries FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_departments_upd BEFORE UPDATE ON departments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_cities_upd BEFORE UPDATE ON cities FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_branches_upd BEFORE UPDATE ON branches FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_stores_upd BEFORE UPDATE ON stores FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER trg_products_upd BEFORE UPDATE ON products FOR EACH ROW EXECUTE FUNCTION set_updated_at();
