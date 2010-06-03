<?php
/**
 * This file is part of phpUnderControl.
 *
 * PHP Version 5.2.0
 *
 * Copyright (c) 2007-2010, Manuel Pichler <mapi@manuel-pichler.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  QualityAssurance
 * @package   Graph
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

require_once dirname( __FILE__ ) . '/AbstractChartTest.php';

/**
 * Test case for the pie chart.
 *
 * @category  QualityAssurance
 * @package   Graph
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 */
class phpucPieChartTest extends phpucAbstractChartTest
{
    /**
     * Tests the line chart render method.
     *
     * @return void
     */
    public function testRender()
    {
        $this->markTestSkippedWhenEzcGraphChartNotExists();

        $dom = new DOMDocument();
        $dom->load( PHPUC_TEST_LOG_FILE );

        $input = new phpucBuildBreakdownInput();
        $input->processLog( new DOMXPath( $dom ) );

        $chart = new phpucPieChart();
        $chart->setInput( $input );

        $file = PHPUC_TEST_DIR . '/test.png';

        $chart->render( 230, 420, $file );

        $this->assertFileExists( $file );
    }

    /**
     * testRenderWithNumberOfEntriesContainsAllEntries
     * 
     * @return void
     */
    public function testRenderWithNumberOfEntriesContainsAllEntries()
    {
        $xpath = $this->renderChartAndReturnXPath( 42, 23 );
        $this->assertEquals( 1, $xpath->query( '//svg:g/svg:text[text() = "42 (100.0%)"]' )->length );
    }

    /**
     * testRenderWithoutNumberOfEntriesContainsAllEntries
     *
     * @return void
     */
    public function testRenderWithoutNumberOfEntriesContainsAllEntries()
    {
        $xpath = $this->renderChartAndReturnXPath( 42 );
        $this->assertEquals( 1, $xpath->query( '//svg:g/svg:text[text() = "42 (100.0%)"]' )->length );
    }

    /**
     * Creates an input instance.
     *
     * @return phpucAbstractInput
     */
    protected function createInput()
    {
        return new phpucBuildBreakdownInput();
    }

    /**
     * Creates a chart instance.
     *
     * @return phpucChartI
     */
    protected function createChart()
    {
        return new phpucPieChart();
    }
}