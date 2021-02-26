<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226024151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, lattitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, nom_agence VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom_complet VARCHAR(255) NOT NULL, num_cin VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, agence_id INT NOT NULL, numero VARCHAR(255) NOT NULL, solde INT NOT NULL, date_creation DATE NOT NULL, statut TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CFF65260D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_retrait_id INT DEFAULT NULL, client_depot_id INT DEFAULT NULL, compte_depot_id INT DEFAULT NULL, user_depot_id INT NOT NULL, compte_retrait_id INT DEFAULT NULL, client_retrait_id INT DEFAULT NULL, montant INT NOT NULL, date_depot DATE DEFAULT NULL, date_retrait DATE DEFAULT NULL, code_transfert VARCHAR(255) DEFAULT NULL, frais INT DEFAULT NULL, frais_depot INT DEFAULT NULL, frais_retrait INT DEFAULT NULL, frais_etat INT DEFAULT NULL, frais_systeme INT DEFAULT NULL, INDEX IDX_723705D1D99F8396 (user_retrait_id), INDEX IDX_723705D1ABF6E41B (client_depot_id), INDEX IDX_723705D17A04723 (compte_depot_id), INDEX IDX_723705D1659D30DE (user_depot_id), INDEX IDX_723705D1B6EC9AC4 (compte_retrait_id), INDEX IDX_723705D1EEAC783B (client_retrait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B3450FF010 (telephone), INDEX IDX_1D1C63B3D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_compte (utilisateur_id INT NOT NULL, compte_id INT NOT NULL, INDEX IDX_AE5B68EBFB88E14F (utilisateur_id), INDEX IDX_AE5B68EBF2C56620 (compte_id), PRIMARY KEY(utilisateur_id, compte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1ABF6E41B FOREIGN KEY (client_depot_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17A04723 FOREIGN KEY (compte_depot_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1659D30DE FOREIGN KEY (user_depot_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B6EC9AC4 FOREIGN KEY (compte_retrait_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EEAC783B FOREIGN KEY (client_retrait_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE utilisateur_compte ADD CONSTRAINT FK_AE5B68EBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_compte ADD CONSTRAINT FK_AE5B68EBF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260D725330D');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D725330D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1ABF6E41B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EEAC783B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D17A04723');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B6EC9AC4');
        $this->addSql('ALTER TABLE utilisateur_compte DROP FOREIGN KEY FK_AE5B68EBF2C56620');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D99F8396');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1659D30DE');
        $this->addSql('ALTER TABLE utilisateur_compte DROP FOREIGN KEY FK_AE5B68EBFB88E14F');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_compte');
    }
}
