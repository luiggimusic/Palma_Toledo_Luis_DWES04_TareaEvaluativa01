-- Active: 1735228839859@@127.0.0.1@3306@palma_toledo_luis_dwes04_tareaevaluativa01
--
-- Base de datos: `Palma_Toledo_Luis_DWES04_TareaEvaluativa01`
--


-- Para poder hacer pruebas y ROLLBACKs
-- SET autocommit = 0;
-- START TRANSACTION;
-- ROLLBACK;


DROP TABLE IF EXISTS `movements`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `productsCategories`;
DROP TABLE IF EXISTS `movementsTypes`;
DROP TABLE IF EXISTS `inventory`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `departments`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `productsCategories`
--
CREATE TABLE IF NOT EXISTS `productsCategories` (
    `categoryId` VARCHAR(5) PRIMARY KEY,
    `categoryName` VARCHAR(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `productsCategories`
--
INSERT INTO
    `productsCategories` (`categoryId`, `categoryName`)
VALUES
    ('PK', 'Packaging'),
    ('FP', 'Finished part'),
    ('RM', 'Raw material'),
    ('TO', 'Tooling'),
    ('UT', 'Utils'),
    ('OF', 'Office'),
    ('CL', 'Clothes');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `products`
--
CREATE TABLE IF NOT EXISTS `products` (
    `productCode` VARCHAR(20) PRIMARY KEY,
    `productName` VARCHAR(50) NOT NULL,
    `categoryId` VARCHAR(5) NOT NULL,
    CONSTRAINT `PROD_CAT_FK` FOREIGN KEY (`categoryId`) REFERENCES `productsCategories` (`categoryId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `products`
--
INSERT INTO
    `products` (`productCode`, `productName`, `categoryId`)
    VALUES
        ('3001-01-0005','CAIXA 280X198X120MM','PK'),
        ('3001-01-0015','SEPARADOR 450X270','PK'),
        ('3001-01-0017','TAPA CAIXA 480X285X45MM EXTERIOR','PK'),
        ('1716186-00-C-FAB','BALANCE RING, OUTLET, ROTOR,PM291','FP'),
        ('1716187-00-D-FAB','BALANCE RING, INLET, ROTOR, PM291, GROOVED','FP'),
        ('FC1H2K8AAA02-FAB','F/B-A/C-BMW/M','FP'),
        ('FC1N0F2S1A02','F/B-A/C-VW/M-90º BRAZED NW6','FP'),
        ('FC1H2K8AAA-PXI','SEMIFP P2176-1-S','RM'),
        ('FC1K4K8PAB-PXI','SEMIFP P2139-1-S','RM'),
        ('FC1N0F2S1B-PXI','SEMIFP P2141-S','RM'),
        ('1716186-00-P-FAB','BALANCE RING, OUTLET, ROTOR,PM291','FP'),
        ('FC1N0F2S1C-PXI','SEMIFP P2143-S','RM');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `movementsTypes`
--
CREATE TABLE IF NOT EXISTS `movementsTypes` (    `movementId` VARCHAR(10) PRIMARY KEY,
    `movementName` VARCHAR(30)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `movementsTypes`
--
INSERT INTO
    `movementsTypes` (`movementId`, `movementName`)
    VALUES
        ('PU', 'Purchase'),
        ('SA', 'Sale'),
        ('TR', 'Transfer'),
        ('SC', 'Scrap'),
        ('PA', 'Positive adjustment'),
        ('NA', 'Negative adjustment'),
        ('MC', 'Material consumption');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `movements`
--
CREATE TABLE IF NOT EXISTS `movements` (
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

INSERT INTO `movements` (`productCode`, `fromBatchNumber`, `toBatchNumber`, `fromLocation`, `toLocation`, `quantity`, `movementId`, `movementDate`, `customer`, `supplier`)
    VALUES 
        ('3001-01-0015', '10000254', '10000254', '', 'EMBAL', 50, 'PU', '2024/03/15', '', 'Rido'),
        ('FC1N0F2S1A02', '20240809-221', '', 'ENCAIXAT', '', 145, 'SA', '2024/04/04', 'Hanon', ''),
        ('FC1N0F2S1B-PXI', '10000201', '10000206', 'M221', 'M221', 1235, 'TR', '2024/04/23', '', ''),
        ('FC1H2K8AAA02-FAB', '20241111-215', '20241111-215', '', 'ENCAIXAT', 98, 'PU', '2024/05/03', '', 'PXI China'),
        ('1716187-00-D-FAB', '20241209-204', '', 'ENCAIXAT', '', 56, 'SA', '2024/06/05', 'Tesla', ''),
        ('3001-01-0017', '10000254', '10000254', 'EMBAL', 'EMBAL', 40, 'TR', '2024/07/05', '', ''),
        ('FC1K4K8PAB-PXI', '20233552', '20233552', '', 'M220B', 458, 'PU', '2024/08/06', '', 'PXI China'),
        ('1716186-00-P-FAB', '20241202-201', '', 'ENCAIXAT', '', 28, 'SA', '2024/08/09', 'Tesla', ''),
        ('FC1H2K8AAA-PXI', '10000254', '10000254', 'M215', 'M215', 2136, 'TR', '2024/09/18', '', ''),
        ('3001-01-0005', '10000254', '10000254', '', 'EMBAL', 30, 'PU', '2024/10/10', '', 'Rido'),
        ('FC1N0F2S1B-PXI', '10000206', '10000206', 'M221', 'M222', 123, 'TR', '2024/10/20', '', ''),
        ('FC1N0F2S1B-PXI', '10000205', '10000206', 'M215', 'M221', 145, 'TR', '2024/10/22', '', ''),
        ('FC1N0F2S1B-PXI', '10000205', '10000206', '', 'M221', 1235, 'PU', '2024/10/23', '', 'PXI China'),
        ('FC1N0F2S1B-PXI', '10000205', '10000210', 'M221', 'M521', 28, 'TR', '2024/11/11', '', ''),
        ('FC1N0F2S1B-PXI', '10000205', '10000210', 'M221', 'M221', 125, 'TR', '2024/11/11', '', ''),
        ('FC1N0F2S1B-PXI', '1000020', '10000210', 'M221', 'M221', 1235, 'TR', '2024/11/23', '', ''),
        ('1716186-00-P-FAB', '10000254', '', 'M215', '', 480, 'SA', '2024/11/26', 'Hanon', ''),
        ('FC1N0F2S1B-PXI', '', '100002099996', '', 'M221', 1235, 'PU', '2024/12/01', '', 'PXI China'),
        ('FC1N0F2S1B-PXI', '', '10000206', '', 'M221', 1235, 'PU', '2024/12/03', '', 'PXI China'),
        ('1716186-00-P-FAB', '10000254', '10000255', 'M215', 'M897', 10, 'TR', '2024/12/11', '', '');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `stock`
--
CREATE TABLE IF NOT EXISTS `inventory` (
    `id` INT(10) NOT NULL,
    `productCode` VARCHAR(20) PRIMARY KEY,
    `batchNumber` VARCHAR(10) DEFAULT NULL,
    `location` VARCHAR(10) DEFAULT NULL,
    `stock` INT NOT NULL

) ENGINE = InnoDB DEFAULT CHARSET = latin1;
    -- CONSTRAINT `STK_PROD_FK` FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,

--
-- Volcado de datos para la tabla `stock`
--
INSERT INTO
    `inventory` (`id`,`productCode`,`batchNumber`,`location`,`stock`)
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
        (9, 'FC1N0F2S1B-PXI', '10000206', 'M221', 33),
        (10, '1716186-00-P-FAB', '10000254', 'M215', 500),
        (11, 'FC1N0F2S1C-PXI', '10000206', 'M221', 33);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `departments`
--
CREATE TABLE IF NOT EXISTS `departments` (
    `id` int (5) NOT NULL,
    `departmentId` VARCHAR(5) PRIMARY KEY,
    `departmentName` VARCHAR(30) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `departments`
--
INSERT INTO
    `departments` (`id`, `departmentId`, `departmentName`)
    VALUES
        (0, 'PL', 'Planning'),
        (1, 'PU', 'Purchases'),
        (2, 'OP', 'Operations'),
        (3, 'OPM', 'Operations Manager'),
        (4, 'PRR', 'Production Responsible'),
        (5, 'PRM', 'Production Manager'),
        (6, 'SAL', 'Sales');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `users`
--
CREATE TABLE IF NOT EXISTS `users` (
    `name` VARCHAR(20) NOT NULL,
    `surname` VARCHAR(30) NOT NULL,
    `dni` VARCHAR(9) PRIMARY KEY,
    `dateOfBirth` DATE NOT NULL,
    `departmentId` VARCHAR(5) NOT NULL,
    CONSTRAINT `USR_DEP_FK` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `users`
--
INSERT INTO
    `users` (`name`,`surname`,`dni`,`dateOfBirth`,`departmentId`)
    VALUES
        ('Manuel','Lopez Gonzalez','44359406L','1981/12/17','PL'),
        ('Juan','Perez','04622363F','1985/10/15','PL'),
        ('Marta','Ruiz Jimenez','76439859A','1978/11/03','PU'),
        ('Rafael','Alvarez Romero','10299255Q','2003/05/01','OP'),
        ('Alejandro','Navarro Gutierrez','24228259J','1999/10/10','OPM'),
        ('Rosa','Torres Ramos','23361012G','1986/05/01','PRR'),
        ('Daniel','Serrano Molina','66150847H','1980/10/07','PRM');


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
    `categoryId` = "of";

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
FROM stock
WHERE productCode NOT IN (SELECT productCode FROM products);

select * from departments;
select * from users;
select name, surname, `departmentName` from users u inner join departments d on u.`departmentId`=d.`departmentId`;


UPDATE users set `departmentId` ="OP" WHERE name = "juan";
UPDATE users set `departmentId` ="OP" WHERE name = "manuel";
UPDATE departments set `departmentId` ="PRUEB" WHERE departmentId = "OP";
DELETE from departments where `departmentId`="PRUEB";