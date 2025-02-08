-- Active: 1735228839859@@127.0.0.1@3306@palma_toledo_luis_dwes04_tareaevaluativa01
--
-- Base de datos: `Palma_Toledo_Luis_DWES04_TareaEvaluativa01`
--

CREATE DATABASE IF NOT EXISTS palma_toledo_luis_dwes04_tareaevaluativa01;
USE palma_toledo_luis_dwes04_tareaevaluativa01;


DROP TABLE IF EXISTS `movements`;
DROP TABLE IF EXISTS `movementTypes`;
DROP TABLE IF EXISTS `inventory`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `productCategories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `departments`;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `productCategories`
--
CREATE TABLE IF NOT EXISTS `productCategories` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `productCategoryId` VARCHAR(5) PRIMARY KEY,
    `productCategoryName` VARCHAR(30) NOT NULL,
    CONSTRAINT `PRODCAT_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `productCategories`
--
INSERT INTO
    `productCategories` (`productCategoryId`, `productCategoryName`)
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
    `id` INT NOT NULL AUTO_INCREMENT,
    `productCode` VARCHAR(20) PRIMARY KEY,
    `productName` VARCHAR(50) NOT NULL,
    `productCategoryId` VARCHAR(5) NOT NULL,
    CONSTRAINT `PROD_CAT_FK` FOREIGN KEY (`productCategoryId`) REFERENCES `productCategories` (`productCategoryId`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `PROD_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `products`
--
INSERT INTO
    `products` (`productCode`, `productName`, `productCategoryId`)
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
CREATE TABLE IF NOT EXISTS `movementTypes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `movementTypeId` VARCHAR(10) PRIMARY KEY,
    `movementTypeName` VARCHAR(30),
    CONSTRAINT `MOVTYP_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `movementsTypes`
--
INSERT INTO
    `movementTypes` (`movementTypeId`, `movementTypeName`)
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
    `id` INT NOT NULL AUTO_INCREMENT,
    `productCode` VARCHAR(20) NOT NULL,
    `fromBatchNumber` VARCHAR(10) DEFAULT NULL,
    `toBatchNumber` VARCHAR(10) DEFAULT NULL,
    `fromLocation` VARCHAR(10) DEFAULT NULL,
    `toLocation` VARCHAR(10) DEFAULT NULL,
    `quantity` INT NOT NULL,
    `movementTypeId` VARCHAR(10) NOT NULL,
    `movementDate` DATE NOT NULL,
    `customer` VARCHAR(20) DEFAULT NULL,
    `supplier` VARCHAR(20) DEFAULT NULL,
    CONSTRAINT `MOV_PROD_FK` FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `MOV_MTYPES_FK` FOREIGN KEY (`movementTypeId`) REFERENCES `movementTypes` (`movementTypeId`),
    CONSTRAINT `MOV_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;


--
-- Volcado de datos para la tabla `movements`
--

INSERT INTO `movements` (`productCode`, `fromBatchNumber`, `toBatchNumber`, `fromLocation`, `toLocation`, `quantity`, `movementTypeId`, `movementDate`, `customer`, `supplier`)
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
-- Estructura de tabla para la tabla `inventory`
--
CREATE TABLE IF NOT EXISTS `inventory` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `productCode` VARCHAR(20) PRIMARY KEY,
    `batchNumber` VARCHAR(10) DEFAULT NULL,
    `location` VARCHAR(10) DEFAULT NULL,
    `stock` INT NOT NULL,
    CONSTRAINT `INV_PROD_FK` FOREIGN KEY (`productCode`) REFERENCES `products` (`productCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `INV_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `inventory`
--
INSERT INTO
    `inventory` (`productCode`,`batchNumber`,`location`,`stock`)
    VALUES
        ('3001-01-0005', '10000254', 'EMBAL', 81),
        ('3001-01-0015', '10000254', 'EMBAL', 696),
        ('3001-01-0017', '10000254', 'EMBAL', 178),
        ('1716186-00-C-FAB','20241202-201','ENCAIXAT',113),
        ('1716187-00-D-FAB','20241209-204','ENCAIXAT',258),
        ('FC1H2K8AAA02-FAB','20241111-215','ENCAIXAT',25),
        ('FC1N0F2S1A02', '20240809', 'ENCAIXAT', 15),
        ('FC1H2K8AAA-PXI', '10000254', 'M215', 4),
        ('FC1K4K8PAB-PXI', '20233552', 'M220B', 124),
        ('FC1N0F2S1B-PXI', '10000206', 'M221', 33),
        ('1716186-00-P-FAB', '10000254', 'M215', 500),
        ('FC1N0F2S1C-PXI', '10000206', 'M221', 33);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `departments`
--
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `departmentId` VARCHAR(5) PRIMARY KEY,
    `departmentName` VARCHAR(30) NOT NULL,
    CONSTRAINT `DEP_UNQ` UNIQUE (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

--
-- Volcado de datos para la tabla `departments`
--
INSERT INTO
    `departments` (`departmentId`, `departmentName`)
    VALUES
        ('PL', 'Planning'),
        ('PU', 'Purchases'),
        ('OP', 'Operations'),
        ('OPM', 'Operations Manager'),
        ('PRR', 'Production Responsible'),
        ('PRM', 'Production Manager'),
        ('SAL', 'Sales');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `users`
--
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20) NOT NULL,
    `surname` VARCHAR(30) NOT NULL,
    `dni` VARCHAR(9) PRIMARY KEY,
    `dateOfBirth` DATE NOT NULL,
    `departmentId` VARCHAR(5) NOT NULL,
    CONSTRAINT `USR_DEP_FK` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT `USR_UNQ` UNIQUE (`id`)
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

