<?php

namespace Fresh\ACFMarkdownField;

class Helpers
{
    /**
     * Get the plugin directory URI
     *
     * @param string $path
     * @return string
     */
    public function getPluginURI( string $path = '' )
    {
        $path = ltrim( $path, '/' );
        return trailingslashit( FRESH_ACF_MARKDOWN_FIELD_PLUGIN_URL ) . $path;
    }

    /**
     * Get the plugin directory path
     *
     * @param string $path
     * @return string
     */
    public function getPluginPath( string $path = '' )
    {
        $path = ltrim( $path, '/' );
        return trailingslashit( FRESH_ACF_MARKDOWN_FIELD_PLUGIN_PATH ) . $path;
    }

    /**
     * Get the URL to an asset
     *
     * @param string $asset
     * @return string
     */
    public function getAssetsUrl( $asset = '' ) 
    {
        $asset = ltrim( $asset, '/' );

        // If the manifest contains the requested file, return the hashed name from the dist dir.
        if ( $asset && ($dist_asset = $this->checkAssetManifestFor( $asset )) )
        {
            $asset = $dist_asset;
        }
        
        return $this->getPluginURI( '/assets/' . $asset );
    }

    /**
     * Checks the asset mix-manifest.json for a matching file and returns the hashed filename if it exists.
     * 
     * @param string $filename The requested asset file
     * @return mixed False if not found, or the hashed filename if it exists in the manifest.
     */
    protected function checkAssetManifestFor( string $filename )
    {
        // The dist directory name where compiled/rev'd assets are written to.
        $dist_dirname = 'dist';
        // Strip leading slash:
        $filename = ltrim( $filename, '/' );
        // Add dir slash:
        $dist_dirname = trailingslashit( $dist_dirname );
        // Get and cache the decoded manifest so that we only read it in once.
        static $manifest = null;
        if ( null === $manifest )
        {
            $manifest_path = $this->getPluginPath( 'assets/'.$dist_dirname.'mix-manifest.json' );
            $manifest = file_exists( $manifest_path ) ? json_decode( file_get_contents( $manifest_path ), true ) : [];
        }
        // Check if the manifest file exists (i.e. during development it will not).
        // Check if the file is actually in the 'dist/' directory (sometimes we request assets that are located outside of the
        // dist/ directory), in which case the manifest file is irrelevant:
        if ( is_array($manifest) && (mb_substr($filename, 0, strlen($dist_dirname)) == $dist_dirname) )
        {
            // Strip 'dist/' dir from our requested file.
            // The mix-manifest.json contents are relative to the dist/ directory, and so file names do not include the 'dist/' path.
            // Note that laravel mix applies a leading slash to all paths, so include that in the lookup:
            $filename = '/' . substr( $filename, strlen($dist_dirname) );
            // If the manifest contains the requested file, return the hashed name (remembering to re-insert the 'dist/' dirname) else return false for no match.
            return array_key_exists( $filename, $manifest ) ? ( rtrim($dist_dirname, '/') . $manifest[ $filename ]) : false;
        }
        return false;
    }
}
