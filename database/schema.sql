CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  base_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sizes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price_modifier DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS fillings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price_modifier DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS toppings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price_modifier DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS decorations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price_modifier DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(120) NOT NULL,
  customer_phone VARCHAR(30) NOT NULL,
  product_id INT NOT NULL,
  size_id INT NOT NULL,
  filling_id INT NOT NULL,
  topping_id INT NOT NULL,
  decoration_id INT NOT NULL,
  custom_text VARCHAR(200) DEFAULT NULL,
  delivery_date DATE NOT NULL,
  delivery_time VARCHAR(30) NOT NULL,
  observations TEXT,
  estimated_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  status VARCHAR(50) NOT NULL DEFAULT 'Nuevo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT
);

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(120) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO settings (setting_key, setting_value) VALUES
('whatsapp_number', '56900000000'),
('min_days_anticipation', '2'),
('delivery_hours', '["10:00 - 12:00","12:00 - 14:00","14:00 - 16:00","16:00 - 18:00"]');

INSERT INTO sizes (name, price_modifier, active) VALUES
('10 personas', 0, 1),
('15 personas', 5000, 1),
('20 personas', 10000, 1),
('30 personas', 15000, 1),
('40 personas', 20000, 1),
('50 personas', 25000, 1);

INSERT INTO fillings (name, price_modifier, active) VALUES
('Manjar', 3000, 1),
('Crema', 2000, 1),
('Frutilla', 2500, 1),
('Mousse de chocolate', 3500, 1),
('Crema de limón', 2800, 1),
('Frambuesa', 3000, 1);

INSERT INTO toppings (name, price_modifier, active) VALUES
('Chantilly', 2000, 1),
('Ganache', 2500, 1),
('Buttercream', 2200, 1),
('Fondant', 4000, 1);

INSERT INTO decorations (name, price_modifier, active) VALUES
('Cumpleaños', 4000, 1),
('Infantil', 5000, 1),
('Bautizo', 4500, 1),
('Matrimonio', 7000, 1),
('Aniversario', 5500, 1),
('Personalizada', 8000, 1);

INSERT INTO products (name, description, image, base_price, active) VALUES
('Torta Chocolate', 'Bizcocho húmedo con ganache y cobertura suave.', 'assets/img/torta-chocolate.jpg', 20000, 1),
('Torta Vainilla', 'Clásica torta de vainilla con relleno de frutilla.', 'assets/img/torta-vainilla.jpg', 18000, 1),
('Torta Red Velvet', 'Suave, elegante y con un toque premium.', 'assets/img/torta-redvelvet.jpg', 24000, 1);
