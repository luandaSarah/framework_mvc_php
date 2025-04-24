<h1 class="text-center">Toute nos offres</h1>



<div
    class="mt-2 row gy-3">
    <?php foreach ($postes as $poste): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><?= $poste->getTitle(); ?></h2>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted"><?= $poste->getUser()->getFullName(); ?></p>
                    <em class="text-muted d-block mb-3"><?= $poste->getCreatedAt()->format('Y/m/d'); ?></em>
                    <p class="card-text"><?= $poste->getDescription(); ?></p>
                    <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>