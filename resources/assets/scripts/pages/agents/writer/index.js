import Alpine from "alpinejs";
import Tooltip from "@ryangjchandler/alpine-tooltip";
import { content } from "./content.js";

content();

Alpine.plugin(Tooltip.defaultProps({ arrow: false }));

Alpine.start();
