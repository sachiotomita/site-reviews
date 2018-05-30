<?php defined( 'WPINC' ) || die; ?>

<tr class="glsr-field">
	<th scope="row">{{ label }}</th>
	<td>
		<fieldset data-depends="{{ depends }}">
			<legend class="screen-reader-text"><span>{{ legend }}</span></legend>
			{{ field }}
		</fieldset>
	</td>
</tr>