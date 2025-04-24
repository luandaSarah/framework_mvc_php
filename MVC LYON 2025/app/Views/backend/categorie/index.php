<section class="container mt-4">
    <h1 class="text-center">Administration des categories</h1>
    <a href="#" class="btn btn-primary">Créer un catégorie</a>
    <div class="mt-2 row gy-3 table-responsive">
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date de création</th>
                    <th scope="col">Visibilité</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $categorie): ?>
                    <tr>
                        <td><?= $categorie->getName(); ?></td>
                        <td><?= $categorie->getDescription(); ?></td>
                        <td><?= $categorie->getCreatedAt()->format('Y/m/d'); ?></td>
                        <td><?= $categorie->getEnabled() ? 'Actif' : 'Inactif'; ?></td>
                        <td> <a href="#" class="btn btn-warning">Modifier</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>