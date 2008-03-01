<?php
/**
 * This file is part of phpUnderControl.
 * 
 * PHP Version 5.2.4
 *
 * Copyright (c) 2007-2008, Manuel Pichler <mapi@phpundercontrol.org>.
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
 * @package   VersionControl
 * @author    Manuel Pichler <mapi@phpundercontrol.org>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

/**
 * Subversion checkout implementation. 
 *
 * @category  QualityAssurance
 * @package   VersionControl
 * @author    Manuel Pichler <mapi@phpundercontrol.org>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 */
class phpucSubversionCheckout extends phpucAbstractCheckout
{
    /**
     * Performs a subversion checkout.
     *
     * @return void
     * @throws phpucErrorException
     *         If the subversion checkout fails.
     */
    public function checkout()
    {
        $options = ' --no-auth-cache --non-interactive';
        if ( $this->username !== null )
        {
            $options .= " --username {$this->username}";
        }
        if ( $this->password !== null )
        {
            $options .= " --password {$this->password}";
        }
        
        $svn = phpucFileUtil::findExecutable( 'svn' );
        $cmd = escapeshellcmd( "{$svn} co {$options} {$this->url} source" );

        $spec = array(
            0 => array("pipe", "r"),  // stdin 
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w")   // stderr
        );

        $cwd = getcwd();
        $env = array();
        
        $error = '';

        $proc = proc_open( $cmd, $spec, $pipes, $cmd, $env );
        if ( is_resource( $proc ) )
        {
            while ( !feof( $pipes[1] ) )
            {
                fgets( $pipes[1], 128 );
            }
            fclose( $pipes[1] );

            while ( !feof( $pipes[2] ) )
            {
                $error .= fgets( $pipes[2], 128 );
            }
            fclose( $pipes[2] );
            
            proc_close( $proc );            
        }
        
        if ( $error !== '' )
        {
            throw new phpucErrorException( $error );
        }
    }
}