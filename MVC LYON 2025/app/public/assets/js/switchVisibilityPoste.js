document.querySelectorAll("input[data-id").forEach((input) => {
  input.addEventListener("change", async (e) => {
    const { id } = e.target.dataset; //const id = e.target.dataset.id;

    const response = await fetch(`/admin/api/postes/${id}/switch`);
    if (response.ok) {
      const data = await response.json();
      const card = e.target.closest('.card');
      const label = card.querySelector('.js-visibility-text');
      if (data.enabled) {
        card.classList.replace('border-danger', 'border-success');
        label.classList.replace('text-danger', 'text-success')
        label.innerHTML="Actif";
      } else {
        card.classList.replace('border-success', 'border-danger');
        label.innerHTML="Inactif";
        label.classList.replace('text-success', 'text-danger')


      }
    }
  });
});
