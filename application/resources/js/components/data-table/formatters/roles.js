import map from 'lodash/map';
import reduce from 'lodash/reduce';
import defaults from './default';

// Cache for role-ID -> HTML content
// Helpful for displaying lots of data so we don't need to reconstruct it 1000x times over
let linkCache = {};

// const titleCase = (str) => str.replace(/\b\S/g, t => t.toUpperCase());
const orderBy = (roles) => roles.sort().join(',');

export default {
  ...defaults,

  initialize({ roles = []} = {}) {
    linkCache = reduce(roles, (acc, role) => {
      acc[role.id] = `<a href="${role.link}" class="badge badge-${role.color}">${role.name}</a>`;
      return acc;
    }, {});
  },

  format: (roles) => map(roles, id => linkCache[id] || '').join(' '),
  groupBy: orderBy,
  orderBy,

  defaultAggregator: 'count',

  aggregates: {
    count: {
      label: 'Anzahl',
      calculate: roles => roles.length,
      format: 'number',
    },
  },
}
