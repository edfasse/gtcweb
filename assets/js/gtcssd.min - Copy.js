 /**
 * Dev: Huren Vicente Pelembe  Donaldo Manuel Banze & Jose Mario
 * Company: OctaBit
 */


function show_description(button_open, area, button_close) {

	/** 
 	*	Responsavel por mostrar a descricao dos cursos
 	*/
 $(button_open).on('click', function () {
       document.querySelector(area).classList.add("active");
       document.querySelector(button_close).classList.add("active");
       document.querySelector(button_open).classList.add("active");
  });

	/**
	* Responsavel por fechar a area da descricao dos cursos
	*/
 $(button_close).on('click', function () {
     document.querySelector(area).classList.remove("active");
     document.querySelector(button_close).classList.remove("active");
     document.querySelector(button_open).classList.remove("active");
  });
}

// Course 1

show_description('.dropdown-toggle-1','.dropdown-menu-courses','.dropdown-close-switch')

// Course 2

show_description('.dropdown-toggle-2','.dropdown-menu-courses-2','.dropdown-close-switch-2')

// Course 3

show_description('.dropdown-toggle-3','.dropdown-menu-courses-3','.dropdown-close-switch-3')

// Course 4

show_description('.dropdown-toggle-4','.dropdown-menu-courses-4','.dropdown-close-switch-4')

// Course 5

show_description('.dropdown-toggle-5','.dropdown-menu-courses-5','.dropdown-close-switch-5')

// Course 6

show_description('.dropdown-toggle-6','.dropdown-menu-courses-6','.dropdown-close-switch-6')

// Course 7

show_description('.dropdown-toggle-7','.dropdown-menu-courses-7','.dropdown-close-switch-7')

// Course 8

show_description('.dropdown-toggle-8','.dropdown-menu-courses-8','.dropdown-close-switch-8')
// Course 9

show_description('.dropdown-toggle-9','.dropdown-menu-courses-9','.dropdown-close-switch-9')
// Course 10

show_description('.dropdown-toggle-10','.dropdown-menu-courses-10','.dropdown-close-switch-10')
// Course 11

show_description('.dropdown-toggle-11','.dropdown-menu-courses-11','.dropdown-close-switch-11')
	
