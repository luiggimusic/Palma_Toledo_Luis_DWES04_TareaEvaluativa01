-- Active: 1735228839859@@127.0.0.1@3306@palma_toledo_luis_dwes04_tareaevaluativa01
--
-- Base de datos: `Palma_Toledo_Luis_DWES04_TareaEvaluativa01`
--


-- Para poder hacer pruebas y ROLLBACKs
-- SET autocommit = 0;
-- START TRANSACTION;
-- ROLLBACK;


DROP TABLE IF EXISTS `products`;

DROP TABLE IF EXISTS `productsCategories`;

DROP TABLE IF EXISTS `movementsTypes`;

DROP TABLE IF EXISTS `movements`;

DROP TABLE IF EXISTS `stock`;

DROP TABLE IF EXISTS `users`;

DROP TABLE IF EXISTS `departments`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `productsCategories`
--
CREATE TABLE IF NOT EXISTS `productsCategories` (
    `id` int (5) NOT NULL,
    `categoryId` VARCHAR(5) PRIMARY KEY,
    `categoryName` VARCHAR(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `productsCategories`
--
INSERT INTO
    `productsCategories` (`id`, `categoryId`, `categoryName`)
VALUES
    (0, 'PK', 'Packaging'),
    (1, 'FP', 'Finished part'),
    (2, 'RM', 'Raw material'),
    (3, 'TO', 'Tooling'),
    (4, 'UT', 'Utils'),
    (5, 'OF', 'Office'),
    (6, 'CL', 'Clothes');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `products`
--
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(10) NOT NULL,
    `productCode` VARCHAR(20) PRIMARY KEY,
    `productName` VARCHAR(50) NOT NULL,
    `categoryId` VARCHAR(5) NOT NULL,
    CONSTRAINT `PROD_CAT_FK` FOREIGN KEY (`categoryId`) REFERENCES `productsCategories` (`categoryId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `products`
--
INSERT INTO
    `products` (`id`, `productCode`, `productName`, `categoryId`)
    VALUES
        (0,'3001-01-0005','CAIXA 280X198X120MM','PK'),
        (1,'3001-01-0015','SEPARADOR 450X270','PK'),
        (2,'3001-01-0017','TAPA CAIXA 480X285X45MM EXTERIOR','PK'),
        (3,'1716186-00-C-FAB','BALANCE RING, OUTLET, ROTOR,PM291','FP'),
        (4,'1716187-00-D-FAB','BALANCE RING, INLET, ROTOR, PM291, GROOVED','FP'),
        (5,'FC1H2K8AAA02-FAB','F/B-A/C-BMW/M','FP'),
        (6,'FC1N0F2S1A02','F/B-A/C-VW/M-90º BRAZED NW6','FP'),
        (7,'FC1H2K8AAA-PXI','SEMIFP P2176-1-S','RM'),
        (8,'FC1K4K8PAB-PXI','SEMIFP P2139-1-S','RM'),
        (9,'FC1N0F2S1B-PXI','SEMIFP P2141-S','RM'),
        (10,'1716186-00-P-FAB','BALANCE RING, OUTLET, ROTOR,PM291','FP'),
        (11,'FC1N0F2S1C-PXI','SEMIFP P2143-S','RM');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `movementsTypes`
--
CREATE TABLE IF NOT EXISTS `movementsTypes` (
    `id` INT(10) NOT NULL,
    `movementId` VARCHAR(10) PRIMARY KEY,
    `movementName` VARCHAR(30)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `movementsTypes`
--
INSERT INTO
    `movementsTypes` (`id`, `movementId`, `movementName`)
    VALUES
        (0, 'PU', 'Purchase'),
        (1, 'SA', 'Sale'),
        (2, 'TR', 'Transfer'),
        (3, 'SC', 'Scrap'),
        (4, 'PA', 'Positive adjustment'),
        (5, 'NA', 'Negative adjustment'),
        (6, 'MC', 'Material consumption');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `movements`
--
CREATE TABLE IF NOT EXISTS `movements` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `productCode` VARCHAR(20) NOT NULL,
    `fromBatchNumber` VARCHAR(10) DEFAULT NULL,
    `toBatchNumber` VARCHAR(10) DEFAULT NULL,
    `fromLocation` VARCHAR(10) DEFAULT NULL,
    `toLocation` VARCHAR(10) DEFAULT NULL,
    `quantity` INT NOT NULL,
    `movementId` VARCHAR(10) NOT NULL,
    `movementDate` DATE NOT NULL,
    `customer` VARCHAR(20) DEFAULT NULL,
    `supplier` VARCHAR(20) DEFAULT NULL,
    CONSTRAINT `MOV_PROD_FK` FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `MOV_MTYPES_FK` FOREIGN KEY (`movementId`) REFERENCES `movementsTypes` (`movementId`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;


--
-- Volcado de datos para la tabla `movements`
--

INSERT INTO `movements` (`id`,`productCode`, `fromBatchNumber`, `toBatchNumber`, `fromLocation`, `toLocation`, `quantity`, `movementId`, `movementDate`, `customer`, `supplier`)
    VALUES 
        (1,'3001-01-0015', '10000254', '10000254', '', 'EMBAL', 50, 'PU', '2024/03/15', '', 'Rido'),
        (2,'FC1N0F2S1A02', '20240809-221', '', 'ENCAIXAT', '', 145, 'SA', '2024/04/04', 'Hanon', ''),
        (3,'FC1N0F2S1B-PXI', '10000201', '10000206', 'M221', 'M221', 1235, 'TR', '2024/04/23', '', ''),
        (4,'FC1H2K8AAA02-FAB', '20241111-215', '20241111-215', '', 'ENCAIXAT', 98, 'PU', '2024/05/03', '', 'PXI China'),
        (5,'1716187-00-D-FAB', '20241209-204', '', 'ENCAIXAT', '', 56, 'SA', '2024/06/05', 'Tesla', ''),
        (6,'3001-01-0017', '10000254', '10000254', 'EMBAL', 'EMBAL', 40, 'TR', '2024/07/05', '', ''),
        (7,'FC1K4K8PAB-PXI', '20233552', '20233552', '', 'M220B', 458, 'PU', '2024/08/06', '', 'PXI China'),
        (8,'1716186-00-P-FAB', '20241202-201', '', 'ENCAIXAT', '', 28, 'SA', '2024/08/09', 'Tesla', ''),
        (9,'FC1H2K8AAA-PXI', '10000254', '10000254', 'M215', 'M215', 2136, 'TR', '2024/09/18', '', ''),
        (10,'3001-01-0005', '10000254', '10000254', '', 'EMBAL', 30, 'PU', '2024/10/10', '', 'Rido'),
        (11,'FC1N0F2S1B-PXI', '10000206', '10000206', 'M221', 'M222', 123, 'TR', '2024/10/20', '', ''),
        (12,'FC1N0F2S1B-PXI', '10000205', '10000206', 'M215', 'M221', 145, 'TR', '2024/10/22', '', ''),
        (13,'FC1N0F2S1B-PXI', '10000205', '10000206', '', 'M221', 1235, 'PU', '2024/10/23', '', 'PXI China'),
        (14,'FC1N0F2S1B-PXI', '10000205', '10000210', 'M221', 'M521', 28, 'TR', '2024/11/11', '', ''),
        (15,'FC1N0F2S1B-PXI', '10000205', '10000210', 'M221', 'M221', 125, 'TR', '2024/11/11', '', ''),
        (16,'FC1N0F2S1B-PXI', '1000020', '10000210', 'M221', 'M221', 1235, 'TR', '2024/11/23', '', ''),
        (17,'1716186-00-P-FAB', '10000254', '', 'M215', '', 480, 'SA', '2024/11/26', 'Hanon', ''),
        (18,'FC1N0F2S1B-PXI', '', '100002099996', '', 'M221', 1235, 'PU', '2024/12/01', '', 'PXI China'),
        (19,'FC1N0F2S1B-PXI', '', '10000206', '', 'M221', 1235, 'PU', '2024/12/03', '', 'PXI China'),
        (20,'1716186-00-P-FAB', '10000254', '10000255', 'M215', 'M897', 10, 'TR', '2024/12/11', '', '');

delete FROM movements;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `stock`
--
CREATE TABLE IF NOT EXISTS `stock` (
    `id` INT(10) NOT NULL,
    `productCode` VARCHAR(20) NOT NULL,
    `batchNumber` VARCHAR(10) DEFAULT NULL,
    `location` VARCHAR(10) DEFAULT NULL,
    `quantity` INT (5) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `stock`
--
INSERT INTO
    `stock` (`id`,`productCode`,`batchNumber`,`location`,`quantity`)
    VALUES
        (0, '3001-01-0005', '10000254', 'EMBAL', 81),
        (1, '3001-01-0015', '10000254', 'EMBAL', 696),
        (2, '3001-01-0017', '10000254', 'EMBAL', 178),
        (3,'1716186-00-C-FAB','20241202-201','ENCAIXAT',113),
        (4,'1716187-00-D-FAB','20241209-204','ENCAIXAT',258),
        (5,'FC1H2K8AAA02-FAB','20241111-215','ENCAIXAT',25),
        (6, 'FC1N0F2S1A02', '20240809', 'ENCAIXAT', 15),
        (7, 'FC1H2K8AAA-PXI', '10000254', 'M215', 4),
        (8, 'FC1K4K8PAB-PXI', '20233552', 'M220B', 124),
        (9, 'Prueba', 'Prueba', 'Prueba', 41),
        (10, 'FC1N0F2S1B-PXI', '10000206', 'M221', 33),
        (11, '1716186-00-P-FAB', '10000254', 'M215', 500),
        (12, 'FC1N0F2S1C-PXI', '10000206', 'M221', 33);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `users`
--
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT NULL,
    `name` VARCHAR(1024) NULL,
    `surname` VARCHAR(1024) NULL,
    `dni` VARCHAR(1024) NULL,
    `dateOfBirth` VARCHAR(1024) NULL,
    `departmentId` VARCHAR(1024) NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `users`
--
INSERT INTO
    `users` (`id`,`name`,`surname`,`dni`,`dateOfBirth`,`departmentId`)
    VALUES
        (0,'Manuel','Lopez Gonzalez','44359406L','17/12/1981','PL'),
        (1,'Juan','Perez','04622363F','15/10/1985','PL'),
        (2,'Marta','Ruiz Jimenez','76439859A','03/11/1978','PU'),
        (3,'Rafael','Alvarez Romero','10299255Q','05/01/2003','OP'),
        (4,'Alejandro','Navarro Gutierrez','24228259J','11/10/1999','OPM'),
        (5,'Rosa','Torres Ramos','23361012G','01/05/1986','PRR'),
        (6,'Daniel','Serrano Molina','66150847H','07/10/1980','PRM');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `departments`
--
CREATE TABLE IF NOT EXISTS `departments` (
    `id` int (5) NOT NULL,
    `departmenId` VARCHAR(5) NOT NULL,
    `departmentName` VARCHAR(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `departments`
--
INSERT INTO
    `departments` (`id`, `departmenId`, `departmentName`)
    VALUES
        (0, 'PL', 'Planning'),
        (1, 'PU', 'Purchases'),
        (2, 'OP', 'Operations'),
        (3, 'OPM', 'Operations Manager'),
        (4, 'PRR', 'Production Responsible'),
        (5, 'PRM', 'Production Manager'),
        (6, 'SAL', 'Sales');


-- EXTRAS LUIS
SHOW CREATE TABLE products;

SELECT
    *
from
    products p
    inner join productscategories pc on p.`categoryId` = pc.`categoryId`;
SELECT * from movements m inner join products p on p.`productCode` = m.`productCode`;

DELETE from
    productscategories
where
    `categoryId` = "fp";

SELECT
    *
from
    productscategories;

SELECT
    *
from
    products;

SHOW COLUMNS
FROM
    products;
SELECT productCode
FROM movements
WHERE productCode NOT IN (SELECT productCode FROM products);
