
plugin.tx_jvranking_pi1 {
    view {
        # cat=plugin.tx_jvranking_pi1/file; type=string; label=Path to template root (FE)
        templateRootPath = EXT:jv_ranking/Resources/Private/Templates/
        # cat=plugin.tx_jvranking_pi1/file; type=string; label=Path to template partials (FE)
        partialRootPath = EXT:jv_ranking/Resources/Private/Partials/
        # cat=plugin.tx_jvranking_pi1/file; type=string; label=Path to template layouts (FE)
        layoutRootPath = EXT:jv_ranking/Resources/Private/Layouts/
    }
    persistence {
        # cat=plugin.tx_jvranking_pi1//a; type=string; label=Default storage PID
        storagePid =
    }
}
