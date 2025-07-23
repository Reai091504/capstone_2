-- Table for tracking daily inventory snapshots
CREATE TABLE IF NOT EXISTS `inventory_history` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `product_id` int(30) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `date_recorded` date NOT NULL,
  `time_recorded` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `date_recorded` (`date_recorded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for tracking stock out events
CREATE TABLE IF NOT EXISTS `stock_out_log` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `product_id` int(30) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `last_quantity` double NOT NULL,
  `stock_out_date` date NOT NULL,
  `stock_out_time` datetime NOT NULL DEFAULT current_timestamp(),
  `restocked` tinyint(1) NOT NULL DEFAULT 0,
  `restock_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `stock_out_date` (`stock_out_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial inventory snapshot for today
INSERT INTO `inventory_history` (`product_id`, `product_name`, `quantity`, `unit`, `price`, `date_recorded`)
SELECT 
    i.product_id,
    p.product_name,
    i.quantity,
    i.unit,
    i.price,
    CURDATE()
FROM inventory i
JOIN products p ON i.product_id = p.id;

-- Create indexes for better performance
CREATE INDEX idx_inventory_history_date ON inventory_history(date_recorded);
CREATE INDEX idx_stock_out_log_date ON stock_out_log(stock_out_date);
