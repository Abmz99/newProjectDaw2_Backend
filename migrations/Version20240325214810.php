<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325214810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoritos DROP FOREIGN KEY favoritos_ibfk_1');
        $this->addSql('ALTER TABLE favoritos DROP FOREIGN KEY favoritos_ibfk_2');
        $this->addSql('ALTER TABLE obra_contenido DROP FOREIGN KEY obra_contenido_ibfk_1');
        $this->addSql('DROP TABLE favoritos');
        $this->addSql('DROP TABLE obra_contenido');
        $this->addSql('ALTER TABLE obra MODIFY ID INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON obra');
        $this->addSql('ALTER TABLE obra ADD Descripcion LONGTEXT NOT NULL, DROP Genero, CHANGE Titulo Titulo VARCHAR(255) NOT NULL, CHANGE ID ID_obra INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE obra ADD PRIMARY KEY (ID_obra)');
        $this->addSql('ALTER TABLE capitulos DROP FOREIGN KEY capitulos_ibfk_1');
        $this->addSql('ALTER TABLE capitulos CHANGE contenido contenido LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE capitulos ADD CONSTRAINT FK_6622F4586DDF4D15 FOREIGN KEY (ID_obra) REFERENCES Obra (ID_obra)');
        $this->addSql('ALTER TABLE suscriptores DROP FOREIGN KEY suscriptores_ibfk_3');
        $this->addSql('ALTER TABLE suscriptores CHANGE ID_usuario ID_usuario INT DEFAULT NULL, CHANGE ID_suscripcion ID_suscripcion INT DEFAULT NULL, CHANGE ID_obra ID_obra INT DEFAULT NULL');
        $this->addSql('ALTER TABLE suscriptores ADD CONSTRAINT FK_CD79D83A6DDF4D15 FOREIGN KEY (ID_obra) REFERENCES Obra (ID_obra)');
        $this->addSql('DROP INDEX correo ON usuario');
        $this->addSql('ALTER TABLE usuario CHANGE nombre nombre VARCHAR(255) DEFAULT NULL, CHANGE correo correo VARCHAR(255) DEFAULT NULL, CHANGE pswd pswd VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario RENAME INDEX id_rol TO IDX_2265B05D569CFD5F');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoritos (ID_usuario INT DEFAULT NULL, ID_obra INT DEFAULT NULL, INDEX ID_obra (ID_obra), INDEX ID_usuario (ID_usuario)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE obra_contenido (ID_obra_contenido INT AUTO_INCREMENT NOT NULL, ID_obra INT DEFAULT NULL, descripcion TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX ID_obra (ID_obra), PRIMARY KEY(ID_obra_contenido)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoritos ADD CONSTRAINT favoritos_ibfk_1 FOREIGN KEY (ID_usuario) REFERENCES usuario (ID_usuario)');
        $this->addSql('ALTER TABLE favoritos ADD CONSTRAINT favoritos_ibfk_2 FOREIGN KEY (ID_obra) REFERENCES obra (ID)');
        $this->addSql('ALTER TABLE obra_contenido ADD CONSTRAINT obra_contenido_ibfk_1 FOREIGN KEY (ID_obra) REFERENCES obra (ID)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE capitulos DROP FOREIGN KEY FK_6622F4586DDF4D15');
        $this->addSql('ALTER TABLE capitulos CHANGE contenido contenido TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE capitulos ADD CONSTRAINT capitulos_ibfk_1 FOREIGN KEY (ID_obra) REFERENCES obra (ID)');
        $this->addSql('ALTER TABLE Obra MODIFY ID_obra INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON Obra');
        $this->addSql('ALTER TABLE Obra ADD Genero VARCHAR(50) NOT NULL, DROP Descripcion, CHANGE Titulo Titulo VARCHAR(255) DEFAULT \'NULL\', CHANGE ID_obra ID INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE Obra ADD PRIMARY KEY (ID)');
        $this->addSql('ALTER TABLE suscriptores DROP FOREIGN KEY FK_CD79D83A6DDF4D15');
        $this->addSql('ALTER TABLE suscriptores CHANGE ID_usuario ID_usuario INT NOT NULL, CHANGE ID_suscripcion ID_suscripcion INT NOT NULL, CHANGE ID_obra ID_obra INT NOT NULL');
        $this->addSql('ALTER TABLE suscriptores ADD CONSTRAINT suscriptores_ibfk_3 FOREIGN KEY (ID_obra) REFERENCES obra (ID)');
        $this->addSql('ALTER TABLE usuario CHANGE nombre nombre VARCHAR(255) NOT NULL, CHANGE correo correo VARCHAR(255) NOT NULL, CHANGE pswd pswd VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX correo ON usuario (correo)');
        $this->addSql('ALTER TABLE usuario RENAME INDEX idx_2265b05d569cfd5f TO ID_rol');
    }
}
