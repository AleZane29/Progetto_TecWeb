const menuToggle = document.getElementById('menuToggle');
	const navMenu = document.getElementById('navMenu');

	menuToggle.addEventListener('click', () => {
		const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
		menuToggle.setAttribute('aria-expanded', !isExpanded);
		navMenu.classList.toggle('active');

    if (!isExpanded) {
      const firstLink = navMenu.querySelector('.nav-link');
      if (firstLink) {
          setTimeout(() => firstLink.focus(), 300); 
      }
  }
	});

	// Chiudi il menu quando si clicca su un link
	document.querySelectorAll('.nav-menu a').forEach((link) => {
		link.addEventListener('click', () => {
			navMenu.classList.remove('active');
			menuToggle.setAttribute('aria-expanded', 'false');
		});
	});

	// Chiudi il menu con il tasto Escape
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			navMenu.classList.remove('active');
			menuToggle.setAttribute('aria-expanded', 'false');
		}
	});