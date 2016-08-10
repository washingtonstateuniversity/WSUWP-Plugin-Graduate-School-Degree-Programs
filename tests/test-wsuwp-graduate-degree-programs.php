<?php

class WSUWP_Test_Graduate_Degree_Programs extends WP_UnitTestCase {

	/**
	 * @dataProvider data_sanitize_gpa
	 *
	 * @param string $gpa      The GPA string to sanitize.
	 * @param string $expected The string expected from sanitization.
	 */
	public function test_sanitize_gpa( $gpa, $expected ) {
		$gpa = WSUWP_Graduate_Degree_Programs::sanitize_gpa( $gpa );

		$this->assertSame( $gpa, $expected );
	}

	public function data_sanitize_gpa() {
		return array(
			array( '4.4',   '4.4' ),
			array( '4.a',   '4.0' ),
			array( '4',     '4.0' ),
			array( '-1',    '1.0' ),
			array( '-1.3',  '1.3' ),
			array( '4.4.4', '0.0' ),
			array( 'a',     '0.0' ),
			array( 'a.a',   '0.0' ),
			array( '0',     '0.0' ),
			array( '',      '0.0' ),
		);
	}
}
