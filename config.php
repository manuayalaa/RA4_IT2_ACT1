<?php
/**
 * @author Manuel David Ayala Reina
 */

// Array asociativo para festivos en Córdoba, España
$cordobaHolidays = array(
    'currentDay' => date('j'),
    'nationalHolidays' => array(
        1 => array(1, 6), // Año Nuevo y Reyes en enero
        2 => array(28),    // Día de Andalucía en febrero
        4 => array(6,7),     // Jueves Santo en abril
             // Viernes Santo en abril
        5 => array(1),     // Día del Trabajo en mayo
        8 => array(15),    // Asunción de la Virgen en agosto
        10 => array(12,24),   // La Fuensanta (fiesta local) en octubre
          // Día de San Rafael (fiesta local) en octubre
        11 => array(1),    // Todos los Santos en noviembre
        12 => array(6, 8, 25), // Día de la Constitución, Inmaculada Concepción y Navidad en diciembre
    ),
);

?>
