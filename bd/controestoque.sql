-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema controleestoque
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema controleestoque
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `controleestoque` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema ControleEstoque
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ControleEstoque
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ControleEstoque` DEFAULT CHARACTER SET utf8 ;
USE `controleestoque` ;

-- -----------------------------------------------------
-- Table `controleestoque`.`lembretes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controleestoque`.`lembretes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `lembrete` VARCHAR(255) NULL DEFAULT NULL,
  `data_lembrete` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `controleestoque`.`lucros_gastos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controleestoque`.`lucros_gastos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `data` DATE NOT NULL,
  `lucro` DECIMAL(10,2) NOT NULL,
  `gasto` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `controleestoque`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controleestoque`.`usuario` (
  `idUsuario` INT(11) NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(80) NOT NULL,
  `Email` VARCHAR(80) NOT NULL,
  `Password` VARCHAR(80) NOT NULL,
  `Dataregistro` DATE NOT NULL,
  `Permissao` TINYINT(4) NOT NULL,
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `controleestoque`.`produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `controleestoque`.`produto` (
  `CodRefProduto` INT(11) NOT NULL AUTO_INCREMENT,
  `NomeProduto` VARCHAR(80) NOT NULL,
  `Usuario_idUsuario` INT(11) NOT NULL,
  `ValorProduto` DOUBLE NOT NULL,
  `QntProduto` INT(11) NOT NULL,
  `Tipo` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`CodRefProduto`),
  INDEX `fk_Produto_Usuario_idx` (`Usuario_idUsuario` ASC) VISIBLE,
  CONSTRAINT `fk_Produto_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `controleestoque`.`usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 23
DEFAULT CHARACTER SET = utf8;

USE `ControleEstoque` ;

-- -----------------------------------------------------
-- Table `ControleEstoque`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ControleEstoque`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(80) NOT NULL,
  `Email` VARCHAR(80) NOT NULL,
  `Password` VARCHAR(80) NOT NULL,
  `Dataregistro` DATE NOT NULL,
  `Permissao` TINYINT NOT NULL,
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ControleEstoque`.`Produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ControleEstoque`.`Produto` (
  `CodRefProduto` INT NOT NULL AUTO_INCREMENT,
  `NomeProduto` VARCHAR(80) NOT NULL,
  `Usuario_idUsuario` INT NOT NULL,
  `ValorProduto` DOUBLE NOT NULL,
  `QntProduto` INT NOT NULL,
  `Tipo` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`CodRefProduto`),
  INDEX `fk_Produto_Usuario_idx` (`Usuario_idUsuario` ASC) VISIBLE,
  CONSTRAINT `fk_Produto_Usuario`
    FOREIGN KEY (`Usuario_idUsuario`)
    REFERENCES `ControleEstoque`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
