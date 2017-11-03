--
-- Contenu de la table `product_type`
--

INSERT INTO `product_type` (`id`, `name`) VALUES
(1, 'Consommables');

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'BACK'),
(2, 'ADMIN'),
(3, 'MEMBER');

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `phone`, `address`, `birthdate`, `email`, `username`, `password`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Ibrahima', 'SOW', '0102030405', '202 Avenue de Général LECLERC', '1985-05-06 00:00:00', 'sowbiba@hotmail.com', 'sowbiba', 'ed7b9b3734926d3533f1fa3733338a317fa36e7d', '2017-10-29 13:43:40', '2017-10-29 13:43:40', 1);
