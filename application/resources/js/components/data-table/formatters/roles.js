import find from 'lodash/find';
import map from 'lodash/map';
import reduce from 'lodash/reduce';
import defaults from './default';

// Cache for role-ID -> HTML content
// Helpful for displaying lots of data so we don't need to reconstruct it 1000x times over
let linkCache = {};

// simple array of {id, name}
let nameCache = [];

const orderBy = (roles) => roles.sort().join(',');

export default {
  ...defaults,

  initialize({ roles = []} = {}) {
    linkCache = reduce(roles, (acc, role) => {
      acc[role.id] = `<a href="${role.link}" class="badge badge-${role.color}">${role.name}</a>`;
      return acc;
    }, {});

    nameCache = reduce(roles, (acc, { id, name }) => {
      acc.push({ id, name });
      return acc;
    }, []);
  },

  format: (roles) => map(roles, id => linkCache[id] || '').join(' '),
  groupBy: orderBy,
  orderBy,

  filterFunction: query => {
    // Collect all the IDs from matched roles
    const lowercase = query.toLowerCase();
    const ids = reduce(nameCache, (acc, { name, id }) => {
      if (name.toLowerCase().indexOf(lowercase) !== -1) {
        acc.push(id);
      }

      return acc;
    }, []);

    // in case it's an invalid string, we always reject all the rows
    if (ids.length === 0) {
      return () => false;
    }

    // otherwise, just search by ID in each row (instead of the full string)
    return (roles) => roles.length > 0 && find(roles, (id) => ids.includes(id)) !== undefined;
  },

  defaultAggregator: 'count',

  aggregates: {
    count: {
      label: 'Anzahl',
      calculate: roles => roles.length,
      format: 'number',
    },
  },
}
