-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 23 juin 2024 à 22:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `forum`
--

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `etat_de_lecture` tinyint(1) DEFAULT 0,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `id_utilisateur`, `message`, `etat_de_lecture`, `date_creation`) VALUES
(1, 4, 'Votre compte a été bloqué par un administrateur.', 0, '2024-06-23 18:36:25'),
(2, 9, 'Votre compte a été bloqué par un administrateur.', 0, '2024-06-23 18:36:41');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` varchar(100) NOT NULL,
  `nom_auteur` varchar(100) NOT NULL,
  `id_auteur` int(50) NOT NULL,
  `vues` varchar(1000) NOT NULL,
  `nombre_reponses` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `titre`, `description`, `contenu`, `date_publication`, `nom_auteur`, `id_auteur`, `vues`, `nombre_reponses`) VALUES
(3, 'santé', 'dgfyh,ccgf', 'jgjjj', '2024-06-18', 'Eunice Loisse Diby', 3, '', 0),
(7, 'santé', 'medicament traditionnel', 'il y a des medicament traditionnel capable de gerire enormement et tres rapidement', '22-06-2024', 'Eunice Loisse Diby', 3, '', 0),
(8, 'jeux', 'jeux virtuel', 'besoin d\'un casque mais je sais pas quelle marque choisir', '23-06-2024', 'Eunice Loisse Diby', 3, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE `reponse` (
  `id` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `nom_auteur` varchar(255) NOT NULL,
  `id_question` int(11) NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`id`, `id_auteur`, `nom_auteur`, `id_question`, `contenu`) VALUES
(13, 5, 'oula tra', 5, 'coucou'),
(14, 4, 'Koua Bi', 3, 'jourbon'),
(15, 3, 'Eunice Loisse Diby', 2, 'ikjy'),
(16, 3, 'Eunice Loisse Diby', 2, 'tu es la bien venu'),
(17, 4, 'Koua Bi', 2, 'bienvenue'),
(18, 7, 'Okou Viviane', 2, 'salut '),
(25, 3, 'Eunice Loisse Diby', 4, 'salut');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','administrateur') NOT NULL,
  `derniere_connexion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_inscription` varchar(100) NOT NULL,
  `bloquer` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `derniere_connexion`, `date_inscription`, `bloquer`) VALUES
(3, 'Eunice Loisse Diby', 'eunice@gmail.com', '$2y$10$/i0mjEUuztVriWgiEawnwOFFeVr9jGVElQ0tomFPSvA1Lj1bV7Urm', 'utilisateur', '2024-06-23 19:41:03', '23-06-2024 21:41:03', 0),
(4, 'Koua Bi', 'bi@gmail.com', '$2y$10$sN6o9w67I4nrTs469rc9aujIZm1SpVL0RHK0nUTV8XGAzemFJFrsa', 'utilisateur', '2024-06-23 18:36:25', '22-06-2024 00:34:07', 1),
(5, 'oula tra', 'tra@gmail.com', '$2y$10$RbolEygTuhRyuqMXnCJPOetJFbe49pYF.pe6K9E0widnItypFa11S', 'utilisateur', '2024-06-21 19:30:42', '', 0),
(6, 'Edwige Konan', 'edo@gmail.com', '$2y$10$EmRoSWQf1mTbzbd6b4JvQ.t/gGw3nJy9mhMF2QTuKPEJhDHFI8ptS', 'utilisateur', '2024-06-21 19:30:42', '', 0),
(7, 'Okou Viviane', 'vivi@gmail.com', '$2y$10$JTzTQ2NkCOZ6N1Lc5r/sie5kN44v3N7V0D7F2Jq.RuGuED9eFLfYm', 'utilisateur', '2024-06-22 07:57:35', '22-06-2024 09:57:35', 0),
(8, 'Didier Drogba', 'drogba@gmail.com', '$2y$10$xiCxCR7YK2HllacQfJ2b8O0OV1R6nrQ/02.NWtCUGpfLSZ5pdKLea', 'utilisateur', '2024-06-21 23:12:26', '22-06-2024 01:12:26', 0),
(9, 'Amadou Bile', 'bile@gmail.com', '$2y$10$xkQm7PK4S/cTT7y3eD.YuuaFxi7KBU8U.3vRrCHGDdx1zednG7VBu', 'utilisateur', '2024-06-23 18:36:41', '', 1),
(10, 'Diby Moyer', 'moyer@gmail.com', '$2y$10$Yiq2IjE.6bK7KHJ30gtdku8JxNx0uELcKQemIgxzLreyi7/s1YEhK', 'utilisateur', '2024-06-23 17:47:28', '', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `reponse`
--
ALTER TABLE `reponse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
