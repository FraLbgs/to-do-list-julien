-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 14 sep. 2022 à 06:35
-- Version du serveur :  5.7.24
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `to_do_list`
--

-- --------------------------------------------------------

--
-- Structure de la table `have_theme`
--

CREATE TABLE `have_theme` (
  `id_tasks` int(11) NOT NULL,
  `id_themes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `share_task`
--

CREATE TABLE `share_task` (
  `id_users` int(11) NOT NULL,
  `id_tasks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id_tasks` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(6) NOT NULL,
  `date_reminder` date NOT NULL,
  `priority` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id_tasks`, `description`, `done`, `color`, `date_reminder`, `priority`, `id_users`) VALUES
(1, 'Étendre le linge.', 1, '00ff00', '2022-09-07', 1, 1),
(2, 'Acheter de la nourriture pour chat.', 0, '30F6EE', '2022-09-08', 7, 1),
(3, 'Enterrer le chat mort là', 0, 'b1e2bb', '2022-10-09', 5, 1),
(4, 'Faire un potager là où le chat est mort là ', 0, 'b7aec1', '2022-09-28', 6, 1),
(5, 'Récolter les légumes', 0, '00ee22', '2022-11-03', 8, 1),
(58, 'Bagarre avec Franck sur overwatch', 0, '9590bb', '2022-09-13', 1, 1),
(59, 'fdgjyte', 0, 'ffffff', '2022-09-15', 9, 1);

-- --------------------------------------------------------

--
-- Structure de la table `themes`
--

CREATE TABLE `themes` (
  `id_themes` int(11) NOT NULL,
  `name_theme` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `themes`
--

INSERT INTO `themes` (`id_themes`, `name_theme`) VALUES
(1, 'Maison'),
(2, 'Travail'),
(3, 'Loisirs');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_users`, `user_name`, `password`) VALUES
(1, 'Franckien', '1234321');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `have_theme`
--
ALTER TABLE `have_theme`
  ADD PRIMARY KEY (`id_tasks`,`id_themes`),
  ADD KEY `id_themes` (`id_themes`);

--
-- Index pour la table `share_task`
--
ALTER TABLE `share_task`
  ADD PRIMARY KEY (`id_users`,`id_tasks`),
  ADD KEY `id_tasks` (`id_tasks`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_tasks`),
  ADD KEY `id_users` (`id_users`);

--
-- Index pour la table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id_themes`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_tasks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `themes`
--
ALTER TABLE `themes`
  MODIFY `id_themes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `have_theme`
--
ALTER TABLE `have_theme`
  ADD CONSTRAINT `have_theme_ibfk_1` FOREIGN KEY (`id_tasks`) REFERENCES `tasks` (`id_tasks`),
  ADD CONSTRAINT `have_theme_ibfk_2` FOREIGN KEY (`id_themes`) REFERENCES `themes` (`id_themes`);

--
-- Contraintes pour la table `share_task`
--
ALTER TABLE `share_task`
  ADD CONSTRAINT `share_task_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `share_task_ibfk_2` FOREIGN KEY (`id_tasks`) REFERENCES `tasks` (`id_tasks`);

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
